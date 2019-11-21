<?php

namespace App\Command;

use App\Entity\Beer;
use App\Entity\Brewer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;

class ImportBeersCommand extends Command
{
    protected static $defaultName = 'beers:import';

    private $container;

    private $doctrine;

    private $entityManager;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
        $this->doctrine = $this->container->get('doctrine');
        $this->entityManager = $this->doctrine->getManager();
    }


    protected function configure()
    {
        $this
            ->setDescription('Import beers from remote API');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $httpClient = HttpClient::create();
            $response = $httpClient->request('GET', $_ENV['REMOTE_API_BEERS']);
            $aBeers = $response->toArray();

            if(! is_array($aBeers) || count($aBeers) === 0)
                return 0;

            $progressBar = new ProgressBar($output, count($aBeers));

            $output->write("Start import beers from remote API.");
            foreach ($aBeers as $aBeer) {

                $brewer = $this->doctrine->getRepository(Brewer::class)
                    ->findOneBy(['name' => $aBeer['brewer']]);

                if ($brewer instanceof Brewer) {

                    $beer = $this->doctrine->getRepository(Beer::class)
                        ->findOneBy(['name' => $aBeer['name']]);

                    if (! $beer instanceof Beer) {
                        $beer = $this->createBeer($brewer,$aBeer);
                        $this->entityManager->persist($beer);
                        $this->entityManager->flush();
                    }
                } else {
                    $brewer = $this->createBrewer($aBeer);
                    $beer = $this->createBeer($brewer, $aBeer);
                    $this->entityManager->persist($brewer);
                    $this->entityManager->persist($beer);
                    $this->entityManager->flush();
                }

                $progressBar->advance();
            }

            $progressBar->finish();

            $io->success("SUCCESS");

        } catch (TransportException $e) {

        }

        return 0;
    }

    /**
     * @param string $size
     * @param float $price
     * @return float
     */
    private function countPricePerLitre(string $size, float $price): float
    {
        preg_match_all('!\d+!', $size, $matches);

        $litres = (float) $matches[0][0] * $matches[0][1] / 1000;

        $price_per_litre = (float) round($price / $litres, 2);

        return $price_per_litre;
    }

    /**
     * @param array $data
     * @return Brewer|null
     */
    private function createBrewer(array $data): ?Brewer
    {
        $brewer = (new Brewer())
            ->setName($data['brewer']);
        return $brewer;
    }

    /**
     * @param Brewer $brewer
     * @param array $data
     * @return Beer|null
     */
    private function createBeer(Brewer $brewer, array $data): ?Beer
    {
        $beer = (new Beer())
            ->setName($data['name'])
            ->setType($data['type'])
            ->setCountry($data['country'])
            ->setBrewer($brewer)
            ->setImgUrl($data['image_url'])
            ->setPricePerLitre($this->countPricePerLitre($data['size'], $data['price']));
        return $beer;
    }
}

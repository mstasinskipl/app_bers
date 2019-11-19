import React, {Component} from 'react';
import {Route, Switch,Redirect, Link, withRouter} from 'react-router-dom';
import Beers from './Beers';

class Home extends Component {

    render() {
        return (
            <div>
                <Switch>
                    <Route path="/" component={Beers} />
                </Switch>
            </div>
        )
    }
}

export default Home;

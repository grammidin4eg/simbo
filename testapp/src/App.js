import React, {Component} from 'react';
import './App.css';
import TestList from './test-list';

class App extends Component {

    constructor(props) {
        super(props);
        this.testList = new TestList();
    }

    test() {
        console.log('start test');
        this.testList.runTest();
    }

    render() {
        return (
            <div className="App">
                <button onClick={this.test.bind(this)}>TEST</button>
            </div>
        );
    }
}

export default App;

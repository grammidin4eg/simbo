import React, {Component} from 'react';
import './App.css';
import TestList from './test-list';

class App extends Component {

    constructor(props) {
        super(props);
        this.testList = new TestList();
        this.state = {
            list: []
        }
    }

    loadList() {
        console.log('loadList');
        this.testList.getList().then((data, result) => {
            this.testList.consoleResult(data, result);
            this.setState({
                list: data
            });
        }, error => {
            this.testList.consoleError(error);
        })
    }

    errorTest() {
        console.log('error Test');
        this.testList.testError().then(this.testList.consoleResult, this.testList.consoleError);
    }

    addNewItem() {
        console.log('add new item');
        const newStr = 'new test string';
        this.testList.addItem(newStr).then(res => {
            if (res) {
                this.setState((state) => {
                    //todo возвращать новую запись из сервиса
                    const newList =[...state.list, {id: res, text: newStr, done: false, important: false}];
                    return {
                        list: newList
                    }
                });
            }
        }, this.testList.consoleError);
    }

    //todo Формат, разбор Record (данные о пользователе), Список для пользователя, регистрация, вход, имя,
    //проверка скаляра, изменение личных данных пользователя
    //список, добавление, изменение, удаление
    //модуль авторизации
    //тест ошибок: неверный запрос, ошибка в php, легальная ошибка
    //навигация

    render() {
        const list = this.state.list.map(item => {
            return (<div className="App__list__item" key={item.id}>{item.text}</div>);
        });
        return (
            <div className="App">
                <div className="App__buttons">
                    <button onClick={this.loadList.bind(this)}>LOAD LIST</button>
                    <button onClick={this.errorTest.bind(this)}>ERROR TEST</button>
                </div>
                <div className="App__list">
                    {list}
                </div>
                <div className="App__bottom-panel">
                    <input/>
                    <button onClick={this.addNewItem.bind(this)}>ADD</button>
                </div>
            </div>
        );
    }
}

export default App;

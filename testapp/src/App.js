import React, {Component} from 'react';
import './App.css';
import TestList from './test-list';

class App extends Component {

    constructor(props) {
        super(props);
        this.testList = new TestList();
        this.state = {
            list: [],
            addField: ''
        }
    }

    changeAddField(e) {
        this.setState({
            addField: e.target.value
        });
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

    addNewItem(event) {
        console.log('add new item');
        event.preventDefault();
        const newStr = this.state.addField;
        if (!newStr) {
            return;
        }
        this.testList.addItem(newStr).then(res => {
            this.loadList();
            this.setState({
                addField: ''
            });
            // if (res) {
            //     this.setState((state) => {
            //         const newList =[...state.list, {id: res, text: newStr, done: false, important: false}];
            //         return {
            //             list: newList
            //         }
            //     });
            // }
        }, this.testList.consoleError);
    }

    deleteItem(id) {
        console.log('deleteItem', id);
        this.testList.deleteItem(id).then(() => this.loadList());
    }

    setDoneItem(id) {
        console.log('deleteItem', id);
        this.testList.setDoneItem(id).then(() => this.loadList());
    }

    setImportantItem(id) {
        console.log('setImportantItem', id);
        this.testList.setImportantItem(id).then(() => this.loadList());
    }

    //todo Формат, разбор Record (данные о пользователе), Список для пользователя, регистрация, вход, имя,
    //проверка скаляра, изменение личных данных пользователя
    //список, добавление, изменение, удаление
    //модуль авторизации
    //тест ошибок: неверный запрос, ошибка в php, легальная ошибка
    //навигация

    render() {
        const list = this.state.list.map(item => {
            let modClass = '';
            if (item.done) {
                modClass += ' is-done';
            }

            if (item.important) {
                modClass += ' is-important';
            }

            return (
                <tr className="App__list__item" key={item.id}>
                    <td className={'App__list__item__text' + modClass}>{item.text}</td>
                    <td>
                        <button className="App__list__item__done btn btn-info btn-xs fa fa-check" onClick={this.setDoneItem.bind(this, item.id)}></button>
                        <button className="App__list__item__important btn btn-info btn-xs fa fa-exclamation-circle" onClick={this.setImportantItem.bind(this, item.id)}></button>
                        <button className="App__list__item__delete btn btn-danger btn-xs fa fa-trash-o" onClick={this.deleteItem.bind(this, item.id)}></button>
                    </td>
                </tr>
            );
        });
        return (
            <div className="App container-fluid">
                <div className="row">
                    <div className="col-md-2"></div>
                    <div className="col-md-8">
                        <div className="App__buttons container">
                            <button onClick={this.loadList.bind(this)} className="btn btn-info">LOAD LIST</button>
                            <button onClick={this.errorTest.bind(this)} className="btn btn-info">ERROR TEST</button>
                        </div>
                        <div className="App__list">
                            <table><tbody>
                            {list}
                            </tbody></table>
                        </div>
                        <form className="App__bottom-panel" onSubmit={this.addNewItem.bind(this)}>
                            <input type="text" onChange={this.changeAddField.bind(this)} value={this.state.addField}/>
                            <button className="btn btn-primary">ADD</button>
                        </form>
                    </div>
                    <div className="col-md-2"></div>
                </div>
            </div>
        );
    }
}

export default App;

import SimboService from './simbo-service';
import SimboServiceVirtual from './virtual-service';

export default class TestList extends SimboService {
    constructor() {
        super('todo');
    }

    consoleResult(...result) {
        console.log('[RESULT]', ...result);
    }

    consoleError(error) {
        console.error('[ERROR]', error);
    }

    getList() {
        return this.exec('list', {param1: 'param1'}, null);
    }

    testError() {
        return this.exec('testerror', {});
    }

    addItem(newItem) {
        return this.exec('add', {value: newItem});
    }

    deleteItem(id) {
        return this.exec('delete', {id: id});
    }

    setDoneItem(id) {
        return this.exec('done', {id: id});
    }

    setImportantItem(id) {
        return this.exec('important', {id: id});
    }

    getUsetInfo() {

    }

    setUserInfo() {

    }
}
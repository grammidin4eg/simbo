import SimboService from './simbo-service';

export default class TestList extends SimboService {
    constructor() {
        super('todo');
    }

    runTest() {
        this.exec('test', {param1: 'param1'}, null).then(result => {
                console.log('test result', result);
            },
            error => {
                console.error('test error', error);
            });
    }
}
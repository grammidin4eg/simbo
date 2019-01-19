export default class SimboServiceVirtual {
    constructor(object) {
        this.object = object;
        this.lastResult = null;
    }

    exec(_method, _params, _navigation) {
        let data;
        switch (_method) {
            case 'list':
                data = [
                    {id: 0, text: 'Пункт 1. Важно.', done: false, important: true},
                    {id: 1, text: 'Пункт 2. В работе.', done: false, important: false},
                    {id: 2, text: 'Пункт 3. Выполнен.', done: true, important: false}
                    ];
                break;
            case 'testerror':
                data='error';
                break;
            case 'add':
                data=10;
                break;
            default:
                data = null;
                break;
        }
        return new Promise((resolve, reject) => {
            if (data === 'error') {
                reject(new Error('test error'));
            } else {
                resolve(data);
            }
        });
    }
}
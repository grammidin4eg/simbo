import SimboService from './simbo-service';
import SimboServiceVirtual from './virtual-service';

export default class UserService extends SimboService {
    constructor() {
        super('user');
    }

    register() {
        return this.exec('register', {login: 'testLogin', password: '123', name: 'User'});
    }

    login() {
        return this.exec('login', {login: 'testLogin', password: '123'});
    }

    getUsetInfo() {

    }

    setUserInfo() {

    }
}
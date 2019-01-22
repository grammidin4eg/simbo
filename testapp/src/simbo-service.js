export default class SimboService {
    constructor(object) {
        this.object = object;
        this.lastResult = null;
    }

    exec(_method, _params, _navigation) {
        return new Promise((resolve, reject) => {
            const data = {
                'OBJECT': this.object,
                'METHOD': _method,
                'NAVIGATION': _navigation,
                'PARAMS': _params
            };
            /*
            NAVIGATION.pagesize
            NAVIGATION.page
            NAVIGATION.type
            */
            console.log('[REQUEST]', data);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'simBL.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');

            // send the collected data as JSON
            xhr.send(JSON.stringify(data));

            xhr.onreadystatechange = () => {
                if (xhr.readyState !== xhr.DONE) return;

                console.log('[onreadystatechange]', 'status', xhr.status, 'statusText', xhr.statusText, 'responseText', xhr.responseText);
                if (xhr.status !== 200) {
                    //ошибка при получении данных
                    reject(new Error(xhr.statusText));
                } else {
                    //ошибка разбора JSON
                    try {
                        this.lastResult = JSON.parse(xhr.responseText);
                    } catch (e) {
                        console.error(e);
                        reject(new Error(e));
                        return;
                    }
                    //проверка на ошибку от БЛ
                    if (this.lastResult.RESULT !== 'OK') {
                        reject(new Error(this.lastResult.ERROR));
                        return;
                    }

                    //если это RecordSet - объединим DATA, COLUMNS и FORMAT
                    if (this.lastResult.TYPE === 'RECORDSET') {
                        const columns = this.lastResult.COLUMNS;
                        const columnsCount = columns.length;
                        this.lastResult.DATA = this.lastResult.DATA.map(curItem => {
                            let newItem = {};
                            for(let i = 0; i < columnsCount; i++ ) {
                                //todo Формат значений
                                newItem[columns[i]] = this.getFormatField(this.lastResult.FORMAT[i], curItem[i]);
                            }
                            return newItem;
                        });
                    }

                    //все в порядке, отдаем данные
                    console.log('[RESULT]', this.lastResult);
                    resolve(this.lastResult.DATA, this.lastResult);
                }

            }
        });
    }

    getFormatField(format, value) {
        switch (format) {
            case 'INT':
                return parseInt(value, 10);
            case 'BOOLEAN':
                return ((value.toUpperCase() === 'TRUE') || (value === '1') );
            case 'FLOAT':
                return parseFloat(value);
            case 'STRING':
            case 'VOID':
            default:
                return value;
        }
    }
}
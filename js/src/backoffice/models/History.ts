import Model from 'flarum/common/Model';

export default class History extends Model {
    operation = Model.attribute('operation');
    amount = Model.attribute('amount');
    comment = Model.attribute('comment');
    createdAt = Model.attribute('createdAt', Model.transformDate);

    order = Model.hasOne('order');
    user = Model.hasOne('user');

    apiEndpoint() {
        return '/flamarkt/inventory/' + this.data.id;
    }
}

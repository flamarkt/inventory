import Model from 'flarum/common/Model';
import User from 'flarum/common/models/User';
import Order from 'flamarkt/core/common/models/Order';
import Product from 'flamarkt/core/common/models/Product';

export default class History extends Model {
    operation = Model.attribute<string>('operation');
    amount = Model.attribute<number>('amount');
    comment = Model.attribute<string>('comment');
    createdAt = Model.attribute('createdAt', Model.transformDate);

    product = Model.hasOne<Product>('product');
    order = Model.hasOne<Order>('order');
    user = Model.hasOne<User>('user');

    apiEndpoint() {
        return '/flamarkt/inventory/' + (this.data as any).id;
    }
}

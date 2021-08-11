import History from './models/History';
import ProductList from 'flamarkt/core/backoffice/components/ProductList';
import ProductShowPage from 'flamarkt/core/backoffice/pages/ProductShowPage';
import Product from 'flamarkt/core/common/models/Product';
import {extend} from 'flarum/common/extend';
import ItemList from 'flarum/common/utils/ItemList';
import Button from 'flarum/common/components/Button';
import AdjustInventoryModal from './components/AdjustInventoryModal';
import InventoryAmount from './components/InventoryAmount';
import {backoffice} from './compat';

export {
    backoffice,
};

app.initializers.add('flamarkt-inventory', () => {
    app.store.models['flamarkt-inventory-history'] = History;

    extend(ProductList.prototype, 'head', function (columns: ItemList) {
        columns.add('inventory', m('th', 'Inventory'));
    });

    extend(ProductList.prototype, 'columns', function (columns: ItemList, product: Product) {
        columns.add('inventory', m('td', m(InventoryAmount, {
            amount: product.attribute('inventory'),
        })));
    });

    extend(ProductShowPage.prototype, 'fields', function (this: ProductShowPage, fields: ItemList) {
        fields.add('balance', m('.Form-group', [
            m('label', 'Inventory'),
            m('input.FormControl', {
                type: 'number',
                value: this.product.attribute('inventory'),
                readonly: true,
            }),
            Button.component({
                className: 'Button',
                onclick: () => {
                    app.modal.show(AdjustInventoryModal, {
                        product: this.product,
                    });
                },
            }, 'Update inventory'),
        ]));
    });
});

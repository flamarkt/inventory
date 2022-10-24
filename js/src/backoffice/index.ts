import History from './models/History';
import ProductList from 'flamarkt/core/backoffice/components/ProductList';
import ProductShowPage from 'flamarkt/core/backoffice/pages/ProductShowPage';
import {extend} from 'flarum/common/extend';
import Button from 'flarum/common/components/Button';
import AdjustInventoryModal from './components/AdjustInventoryModal';
import InventoryAmount from './components/InventoryAmount';
import {backoffice} from './compat';

export {
    backoffice,
};

app.initializers.add('flamarkt-inventory', () => {
    app.store.models['flamarkt-inventory-history'] = History;

    extend(ProductList.prototype, 'head', function (columns) {
        columns.add('inventory', m('th', app.translator.trans('flamarkt-inventory.backoffice.products.head.inventory')));
    });

    extend(ProductList.prototype, 'columns', function (columns, product) {
        columns.add('inventory', m('td', m(InventoryAmount, {
            amount: product.attribute('inventory'),
        })));
    });

    extend(ProductShowPage.prototype, 'fields', function (fields) {
        const value = this.product!.attribute('inventory');

        fields.add('balance', m('.Form-group', [
            m('label', app.translator.trans('flamarkt-inventory.backoffice.products.field.inventory')),
            m('input.FormControl', {
                type: 'number',
                value,
                readonly: true,
                placeholder: value === null ? app.translator.trans('flamarkt-inventory.backoffice.products.field.inventoryNotTrackedPlaceholder') : '',
            }),
            Button.component({
                className: 'Button',
                onclick: () => {
                    app.modal.show(AdjustInventoryModal, {
                        product: this.product,
                    });
                },
            }, app.translator.trans('flamarkt-inventory.backoffice.products.adjust')),
        ]));
    });
});

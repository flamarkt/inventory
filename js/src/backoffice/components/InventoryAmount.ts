import app from 'flamarkt/backoffice/backoffice/app';
import Component from 'flarum/common/Component';

interface InventoryAmountAttrs {
    amount: number | null
}

export default class InventoryAmount extends Component<InventoryAmountAttrs> {
    view() {
        if (this.attrs.amount === null) {
            return m('em', app.translator.trans('flamarkt-inventory.backoffice.amount.notTracked'));
        }

        // Case number to string including zeros
        return this.attrs.amount + '';
    }
}

import Component from 'flarum/common/Component';

interface InventoryAmountAttrs {
    amount: number | null
}

export default class InventoryAmount extends Component<InventoryAmountAttrs> {
    view() {
        if (this.attrs.amount === null) {
            return m('em', 'not tracked');
        }

        // Case number to string including zeros
        return this.attrs.amount + '';
    }
}

import Modal from 'flarum/common/components/Modal';
import ItemList from 'flarum/common/utils/ItemList';
import Button from 'flarum/common/components/Button';
import Select from 'flarum/common/components/Select';

export default class AdjustInventoryModal extends Modal {
    operation: 'add' | 'set' | 'null' = 'add';
    amount: number = 0;
    comment: string = '';
    saving: boolean = false;

    content() {
        return m('.Modal-body', this.fields().toArray());
    }

    fields(): ItemList {
        const fields = new ItemList();

        fields.add('operation', m('.Form-group', [
            m('local', 'Operation'),
            Select.component({
                options: {
                    add: 'Add',
                    set: 'Set',
                    null: 'Untrack',
                },
                value: this.operation,
                onchange: (value: 'add' | 'set') => {
                    if (value === 'set') {
                        this.amount = this.attrs.product.attribute('inventory') || 0;
                    } else {
                        this.amount = 0;
                    }

                    this.operation = value;
                },
                disabled: this.saving,
            }),
        ]));

        fields.add('amount', m('.Form-group', [
            m('local', 'Amount'),
            m('input.FormControl', {
                type: 'number',
                value: this.operation === 'null' ? '' : this.amount,
                onchange: (event: Event) => {
                    this.amount = (event.target as HTMLInputElement).value;
                },
                disabled: this.saving || this.operation === 'null',
            }),
        ]));

        fields.add('comment', m('.Form-group', [
            m('local', 'Comment'),
            m('textarea.FormControl', {
                value: this.comment,
                onchange: (event: Event) => {
                    this.comment = (event.target as HTMLInputElement).value;
                },
                disabled: this.saving,
            }),
        ]));

        fields.add('submit', m('.Form-group', [
            Button.component({
                type: 'submit',
                className: 'Button Button--primary',
                loading: this.saving,
            }, 'Apply'),
        ]), -10);

        return fields;
    }

    data() {
        return {
            operation: this.operation,
            amount: this.amount,
            comment: this.comment,
        };
    }

    onsubmit(event) {
        event.preventDefault();

        this.saving = true;

        app.request({
            method: 'POST',
            url: app.forum.attribute('apiUrl') + '/flamarkt/products/' + this.attrs.product.id() + '/inventory',
            body: {
                data: {
                    attributes: this.data(),
                },
            },
        }).then(() => {
            this.saving = false;

            m.redraw();

            this.hide();
        }).catch(error => {
            this.saving = false;

            m.redraw();

            throw error;
        });
    }
}

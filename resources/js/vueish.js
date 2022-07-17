export default {
    _components: {},
    components(entries) {
        this._components = entries;

        return this;
    },
    mount() {
        document.querySelectorAll('[o-component]').forEach((el) => {
            const componentName = el.getAttribute('o-component');

            if (!componentName?.trim().length) {
                return;
            }

            const component = this._components[componentName];

            component?.(el);
        });
    },
};

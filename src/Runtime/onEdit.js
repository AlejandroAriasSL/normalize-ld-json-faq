window.addEventListener('elementor:init', () => {

    const updateAllTextEditors = (containerElement) => {
        const widgets = containerElement.querySelectorAll('.elementor-widget-text-editor');
        widgets.forEach(widgetElement => {
            const html = widgetElement.innerHTML?.trim();
            if (!html) return;

            const widgetView = widgetElement.__view || widgetElement.dataset.view || null;
            if (widgetView && widgetView.model) {
                const currentEditor = widgetView.model.attributes.settings.editor || '';
                if (!currentEditor.trim()) {
                    widgetView.model.attributes.settings.editor = html;
                    widgetView.model.trigger('change:settings');
                }
            }
        });
    };

    elementor.hooks.addAction('panel/open_editor/widget', 
    (_, model, view) => {
        const widgetType = model.get('widgetType');

        if (widgetType !== 'nested-accordion' && widgetType !== 'text-editor') return;

        const observer = new MutationObserver(() => updateAllTextEditors(view.$el[0]));
        observer.observe(view.$el[0], { childList: true, subtree: true });

        updateAllTextEditors(view.$el[0]);
    });
});

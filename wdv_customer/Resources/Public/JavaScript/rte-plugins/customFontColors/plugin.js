import { Plugin } from '@ckeditor/ckeditor5-core';

class CustomFontColors extends Plugin {
    init() {
        const editor = this.editor;

         // Font Color
         // Downcast wendet EditorSettings an und macht sie persistent
        editor.conversion.for('downcast').attributeToElement({
            model: 'fontColor',
            view: (modelAttributeValue, { writer }) => {
                if (!modelAttributeValue) return null; // Wichtig: Abbruch bei null wenn die Farbe entfernt wird, was immer passiert, bevor eine gesetzt wird.
                // Finde die entsprechende Farbe in der Konfiguration
                const colorConfig = editor.config.get('fontColor.colors').find(color => color.color === modelAttributeValue);
                const className = colorConfig?.class || colorConfig?.label.toLowerCase().replace(/ /g, '-');
                return writer.createAttributeElement('span', {
                    class: className
                });
            },
            converterPriority: 'high'
        });

        // Upcast lädt persistente Einstellungen in den Editor
        editor.conversion.for('upcast').elementToAttribute({
            view: {
                name: 'span',
                classes: /.*/ // Alle Klassen berücksichtigen
            },
            model: {
                key: 'fontColor',
                value: viewElement => {
                    const classNames = Array.from(viewElement.getClassNames());
                    for (const className of classNames) {
                        const colorConfig = editor.config.get('fontColor.colors').find(color =>
                            color.class === className ||
                            color.label.toLowerCase().replace(/ /g, '-') === className
                        );
                        if (colorConfig) {
                            return colorConfig.color; // Rückgabe des Farbwerts
                        }
                    }
                    return null; // Keine passende Farbe gefunden
                }
            }
        });

        // Font Background Color
        editor.conversion.for('downcast').attributeToElement({
            model: 'fontBackgroundColor',
            view: (modelAttributeValue, { writer }) => {
                if (!modelAttributeValue) return null; // Wichtig: Abbruch bei null wenn die Farbe entfernt wird, was immer passiert, bevor eine gesetzt wird.
                // Finde die entsprechende Farbe in der Konfiguration
                const colorConfig = editor.config.get('fontBackgroundColor.colors').find(color => color.color === modelAttributeValue);
                const className = colorConfig?.class ? `back-${colorConfig.class}` : `back-${colorConfig.label.toLowerCase().replace(/ /g, '-')}`;
                return writer.createAttributeElement('span', {
                    class: className
                });
            },
            converterPriority: 'high'
        });

        editor.conversion.for('upcast').elementToAttribute({
            view: {
                name: 'span',
                classes: /^bg-.*/ // Nur Klassen, die mit "bg-" beginnen
            },
            model: {
                key: 'fontBackgroundColor',
                value: viewElement => {
                    const classNames = Array.from(viewElement.getClassNames());
                    for (const className of classNames) {
                        if (className.startsWith('back-')) {
                            const colorName = className.replace('back-', ''); // Entferne "bg-"
                            const colorConfig = editor.config.get('fontBackgroundColor.colors').find(color =>
                                color.class === colorName ||
                                color.label.toLowerCase().replace(/ /g, '-') === colorName
                            );
                            if (colorConfig) {
                                return colorConfig.color; // Rückgabe des Farbwerts
                            }
                        }
                    }
                    return null; // Keine passende Farbe gefunden
                }
            }
        });


    }
}

export default CustomFontColors;
/* Add buttons related to Markdown to the toolbar */

if (typeof window.toolbar !== 'undefined') {
    toolbar[toolbar.length] = {
        type: "insert",
        title: "Markdown Doctype",
        icon: "../../plugins/commonmark/images/markdown.png",
        key: "",
        insert: "<!DOCTYPE markdown>",
        block: "true"
    };
}

export function copyToClipboard(content){
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(content);
    } else {
        // Fallback for unsupported browsers
        const textarea = document.createElement('textarea');
        textarea.value = content;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
    }
}
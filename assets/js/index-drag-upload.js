const drag_upload = () => {
    return {
        drop: () => {
            event.preventDefault();
            event.stopPropagation();
            uploadNewFiles(event.dataTransfer.files);
        },
        over: () => {
            event.preventDefault();
            event.stopPropagation();
        }
    }
}
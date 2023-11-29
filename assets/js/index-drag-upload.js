const drag_upload = () => {
    return {
        drop: () => {
            event.preventDefault();
            event.stopPropagation();
            const files = event.dataTransfer.files;
            console.log(files);
        },
        over: () => {
            event.preventDefault();
            event.stopPropagation();
        }
    }
}
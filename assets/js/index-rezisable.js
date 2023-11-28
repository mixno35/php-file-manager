const resizableElement = document.getElementById("left-directory-manager");
const resizeHandle = document.getElementById("resize-divider");
let isResizing = false;
let startX, startWidth;

resizeHandle.addEventListener("mousedown", (e) => {
    isResizing = true;
    startX = e.clientX;
    startWidth = parseFloat(getComputedStyle(resizableElement, null).getPropertyValue("width"));

    document.body.style.cursor = "e-resize";
});

document.addEventListener("mousemove", (e) => {
    if (!isResizing) return;
    const newWidth = startWidth + (e.clientX - startX);
    resizableElement.style.width = newWidth + "px";
});

document.addEventListener("mouseup", () => {
    if (isResizing) {
        document.body.style.cursor = "default";
        startWidth = parseFloat(getComputedStyle(resizableElement, null).getPropertyValue("width"));
    }

    isResizing = false;
});
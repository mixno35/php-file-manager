let isDragging = false;
let draggedIsDir = 0;

let movePath = "";
let movePathStart = "";

const drag = () => {
    return {
        start: () => {
            isDragging = true;
            event.dataTransfer.setData("text/plain", "");
            movePathStart = event.target.getAttribute("data-path");
        },
        end: () => {
            isDragging = false;
        },
        enter: () => {
            if (isDragging) {
                draggedIsDir = Number(event.target.getAttribute("data-isdir"));
                movePath = event.target.getAttribute("data-path");
            }

            if (Boolean(draggedIsDir))
                if (movePathStart !== movePath)
                    document.getElementById(event.target.id).classList.add("drag-in");
        },
        leave: () => {
            try {
                document.getElementById(event.target.id).classList.remove("drag-in");
            } catch (e) {}
        },
        drop: () => {
            if (Boolean(draggedIsDir)) {
                if (movePathStart !== movePath) run_command().move(movePathStart, movePath);
            }

            movePath = "";
            movePathStart = "";

            draggedIsDir = 0;

            try {
                document.getElementById(event.target.id).classList.remove("drag-in");
            } catch (e) {}
        },
        live: () => {

        },
        over: () => {
            if (Boolean(draggedIsDir))
                if (movePathStart !== movePath) event.preventDefault();
        }
    }
}
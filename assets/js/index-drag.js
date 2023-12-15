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
            if (select_path().length() < 1) select_path(event.target.getAttribute("data-path")).add();
        },
        end: () => {
            isDragging = false;
            select_path().clear();
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
                if (movePathStart !== movePath) run_command().move(select_path().getAll(), movePath);
            }

            movePath = "";
            movePathStart = "";
            select_path(undefined, () => {
                updateSelectPathsContainer(true);
            }).clear();

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
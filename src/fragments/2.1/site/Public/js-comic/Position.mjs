import Config from "./Config.mjs";

const Position = {
    left: { x: -Config.panels.w / 3, y: -4, flipped: false},
    center: { x: 0, y: -20, flipped: false},
    right: { x: Config.panels.w / 3, y: -4, flipped: true}
};

export default Position;
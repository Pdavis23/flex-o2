class team {
    constructor(element) {
        this.el = element;

        this.init();
    }

    init() {
        console.log(this.el, 'init');
    }
}

export default team;

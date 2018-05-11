export default {
    getIdFromRouteParams: function (that) {
        return that.$f7route.params.id;
    },

    getRouter: function () {
        // return that.$f7route
        // return app.__vue__.$router;
    },

    goToRoute (path) {
        app.f7.router.navigate(path);
    },

    /**
     * If url is /items/:2, return 2
     * @param that
     * @returns {*}
     */
    getIdFromUrl: function () {
        var idWithColon =  this.getRouter().currentRoute.params.id;
        var id;

        if (!idWithColon) return false;

        var index = idWithColon.indexOf(':');

        if (index != -1) {
            id = idWithColon.slice(index+1);
        }

        return id;
    },

    getCurrentPath () {
        return this.getRouter().currentRoute.path;
    },

}

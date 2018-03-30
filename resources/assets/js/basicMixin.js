Vue.mixin({
    methods: {
        getMatchID: function() {
            var url = window.location.href;
            for (var i = url.length - 1; i >= 0; i--) {
                if (url[i] == '/') {
                    break;
                }
            }
            return Number(url.slice(i + 1));
        }
    },
    filters: {
        convertOver: function(x) {
            var over = 0;
            var bowl = 0;
            if (x) {
                over = parseInt(eval(x / 6));
                bowl = eval(x % 6);
                return over + '.' + bowl;
            } else {
                return x;
            }
        }
    }
});
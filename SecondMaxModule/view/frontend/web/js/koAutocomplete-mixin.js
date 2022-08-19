define([], function () {
    var mixin = {
        defaults: {
            searchLength: 5
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
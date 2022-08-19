define(['uiComponent', 'jquery'], function (Component, $) {
    return Component.extend({
        defaults: {
            searchText: '',
            searchLength: 3,
            url: 'index/autocomplete',
            searchResult: []
        },
        initObservable: function () {
            this._super();
            this.observe(['searchText', 'searchResult', 'inputCss']);

            return this;
        },
        initialize: function () {
            this._super();
            this.searchText.subscribe(this.handleAutocomplete.bind(this));
        },
        handleAutocomplete: function (searchValue) {
            if (searchValue.length >= this.searchLength) {
                $.ajax({
                    url: this.url,
                    data: {searchValue: searchValue},
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        this.searchResult(res);
                    }.bind(this)
                });
            }
        }
    });
});
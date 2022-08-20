define(['uiComponent'], function (Component) {
    return Component.extend({
        defaults: {
            timeStr: '00:00:00',
            hour: 0,
            min: 0,
            sec: 0,
            delay: 1000,
            timerId: null,
            numberFormat: null,
            isStarted: false,
            minIntDigits: 2
        },
        initObservable: function () {
            this._super();
            this.observe(['timeStr']);

            return this;
        },
        initialize: function () {
            this._super();
            this.numberFormat = new Intl.NumberFormat(
                'en-US',
                {minimumIntegerDigits: this.minIntDigits}
            );
        },
        handleStart: function () {
            if (!this.isStarted) {
                this.timerId = setInterval(this.tick.bind(this), this.delay);
                this.isStarted = true;
            }
        },
        handlePause: function () {
            if (this.isStarted) {
                clearInterval(this.timerId);
                this.isStarted = false;
            }
        },
        handleStop: function () {
            clearInterval(this.timerId);

            this.min = 0;
            this.hour = 0;
            this.sec = 0;
            this.timeStr('00:00:00');
            this.isStarted = false;
        },
        tick: function () {
            this.sec++;

            if (this.sec >= 60) {
                this.min++;
                this.sec = 0;
            }

            if (this.min >= 60) {
                this.hour++;
                this.min = 0;
            }

            this.timeStr(
                this.numberFormat.format(this.hour) +
                ':' + this.numberFormat.format(this.min) +
                ':' + this.numberFormat.format(this.sec)
            );
        }
    });
});
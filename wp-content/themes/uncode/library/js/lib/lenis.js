"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
(function (global, factory) {
  (typeof exports === "undefined" ? "undefined" : _typeof(exports)) === 'object' && typeof module !== 'undefined' ? module.exports = factory() : typeof define === 'function' && define.amd ? define(factory) : (global = typeof globalThis !== 'undefined' ? globalThis : global || self, global.Lenis = factory());
})(void 0, function () {
  'use strict';

  var version = "1.1.9";

  // Clamp a value between a minimum and maximum value
  function clamp(min, input, max) {
    return Math.max(min, Math.min(input, max));
  }

  // Linearly interpolate between two values using an amount (0 <= t <= 1)
  function lerp(x, y, t) {
    return (1 - t) * x + t * y;
  }

  // http://www.rorydriscoll.com/2016/03/07/frame-rate-independent-damping-using-lerp/
  function damp(x, y, lambda, dt) {
    return lerp(x, y, 1 - Math.exp(-lambda * dt));
  }

  // Calculate the modulo of the dividend and divisor while keeping the result within the same sign as the divisor
  // https://anguscroll.com/just/just-modulo
  function modulo(n, d) {
    return (n % d + d) % d;
  }
  var Animate = /*#__PURE__*/function () {
    function Animate() {
      _classCallCheck(this, Animate);
      this.isRunning = false;
      this.value = 0;
      this.from = 0;
      this.to = 0;
      this.duration = 0;
      this.currentTime = 0;
    }
    _createClass(Animate, [{
      key: "advance",
      value: function advance(deltaTime) {
        var _a;
        if (!this.isRunning) return;
        var completed = false;
        if (this.duration && this.easing) {
          this.currentTime += deltaTime;
          var linearProgress = clamp(0, this.currentTime / this.duration, 1);
          completed = linearProgress >= 1;
          var easedProgress = completed ? 1 : this.easing(linearProgress);
          this.value = this.from + (this.to - this.from) * easedProgress;
        } else if (this.lerp) {
          this.value = damp(this.value, this.to, this.lerp * 60, deltaTime);
          if (Math.round(this.value) === this.to) {
            this.value = this.to;
            completed = true;
          }
        } else {
          this.value = this.to;
          completed = true;
        }
        if (completed) {
          this.stop();
        }
        (_a = this.onUpdate) === null || _a === void 0 ? void 0 : _a.call(this, this.value, completed);
      }
    }, {
      key: "stop",
      value: function stop() {
        this.isRunning = false;
      }
    }, {
      key: "fromTo",
      value: function fromTo(from, to, _ref) {
        var lerp = _ref.lerp,
          duration = _ref.duration,
          easing = _ref.easing,
          onStart = _ref.onStart,
          onUpdate = _ref.onUpdate;
        this.from = this.value = from;
        this.to = to;
        this.lerp = lerp;
        this.duration = duration;
        this.easing = easing;
        this.currentTime = 0;
        this.isRunning = true;
        onStart === null || onStart === void 0 ? void 0 : onStart();
        this.onUpdate = onUpdate;
      }
    }]);
    return Animate;
  }();
  function debounce(callback, delay) {
    var timer;
    return function () {
      var args = arguments;
      var context = this;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, delay);
    };
  }
  var Dimensions = /*#__PURE__*/function () {
    function Dimensions() {
      var _this = this;
      var _ref2 = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
        wrapper = _ref2.wrapper,
        content = _ref2.content,
        _ref2$autoResize = _ref2.autoResize,
        autoResize = _ref2$autoResize === void 0 ? true : _ref2$autoResize,
        _ref2$debounce = _ref2.debounce,
        debounceValue = _ref2$debounce === void 0 ? 250 : _ref2$debounce;
      _classCallCheck(this, Dimensions);
      this.width = 0;
      this.height = 0;
      this.scrollWidth = 0;
      this.scrollHeight = 0;
      this.resize = function () {
        _this.onWrapperResize();
        _this.onContentResize();
      };
      this.onWrapperResize = function () {
        if (_this.wrapper === window) {
          _this.width = window.innerWidth;
          _this.height = window.innerHeight;
        } else if (_this.wrapper instanceof HTMLElement) {
          _this.width = _this.wrapper.clientWidth;
          _this.height = _this.wrapper.clientHeight;
        }
      };
      this.onContentResize = function () {
        if (_this.wrapper === window) {
          _this.scrollHeight = _this.content.scrollHeight;
          _this.scrollWidth = _this.content.scrollWidth;
        } else if (_this.wrapper instanceof HTMLElement) {
          _this.scrollHeight = _this.wrapper.scrollHeight;
          _this.scrollWidth = _this.wrapper.scrollWidth;
        }
      };
      this.wrapper = wrapper;
      this.content = content;
      if (autoResize) {
        this.debouncedResize = debounce(this.resize, debounceValue);
        if (this.wrapper === window) {
          window.addEventListener('resize', this.debouncedResize, false);
        } else {
          this.wrapperResizeObserver = new ResizeObserver(this.debouncedResize);
          this.wrapperResizeObserver.observe(this.wrapper);
        }
        this.contentResizeObserver = new ResizeObserver(this.debouncedResize);
        this.contentResizeObserver.observe(this.content);
      }
      this.resize();
    }
    _createClass(Dimensions, [{
      key: "destroy",
      value: function destroy() {
        var _a, _b;
        (_a = this.wrapperResizeObserver) === null || _a === void 0 ? void 0 : _a.disconnect();
        (_b = this.contentResizeObserver) === null || _b === void 0 ? void 0 : _b.disconnect();
        window.removeEventListener('resize', this.debouncedResize, false);
      }
    }, {
      key: "limit",
      get: function get() {
        return {
          x: this.scrollWidth - this.width,
          y: this.scrollHeight - this.height
        };
      }
    }]);
    return Dimensions;
  }();
  var Emitter = /*#__PURE__*/function () {
    function Emitter() {
      _classCallCheck(this, Emitter);
      this.events = {};
    }
    _createClass(Emitter, [{
      key: "emit",
      value: function emit(event) {
        var callbacks = this.events[event] || [];
        for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
          args[_key - 1] = arguments[_key];
        }
        for (var i = 0, length = callbacks.length; i < length; i++) {
          callbacks[i].apply(callbacks, args);
        }
      }
    }, {
      key: "on",
      value: function on(event, callback) {
        var _this2 = this;
        var _a;
        ((_a = this.events[event]) === null || _a === void 0 ? void 0 : _a.push(callback)) || (this.events[event] = [callback]);
        return function () {
          var _a;
          _this2.events[event] = (_a = _this2.events[event]) === null || _a === void 0 ? void 0 : _a.filter(function (i) {
            return callback !== i;
          });
        };
      }
    }, {
      key: "off",
      value: function off(event, callback) {
        var _a;
        this.events[event] = (_a = this.events[event]) === null || _a === void 0 ? void 0 : _a.filter(function (i) {
          return callback !== i;
        });
      }
    }, {
      key: "destroy",
      value: function destroy() {
        this.events = {};
      }
    }]);
    return Emitter;
  }();
  var LINE_HEIGHT = 100 / 6;
  var VirtualScroll = /*#__PURE__*/function () {
    function VirtualScroll(element, _ref3) {
      var _this3 = this;
      var _ref3$wheelMultiplier = _ref3.wheelMultiplier,
        wheelMultiplier = _ref3$wheelMultiplier === void 0 ? 1 : _ref3$wheelMultiplier,
        _ref3$touchMultiplier = _ref3.touchMultiplier,
        touchMultiplier = _ref3$touchMultiplier === void 0 ? 1 : _ref3$touchMultiplier;
      _classCallCheck(this, VirtualScroll);
      this.lastDelta = {
        x: 0,
        y: 0
      };
      this.windowWidth = 0;
      this.windowHeight = 0;
      this.onTouchStart = function (event) {
        var _ref4 = event.targetTouches ? event.targetTouches[0] : event,
          clientX = _ref4.clientX,
          clientY = _ref4.clientY;
        _this3.touchStart.x = clientX;
        _this3.touchStart.y = clientY;
        _this3.lastDelta = {
          x: 0,
          y: 0
        };
        _this3.emitter.emit('scroll', {
          deltaX: 0,
          deltaY: 0,
          event: event
        });
      };
      this.onTouchMove = function (event) {
        var _a, _b, _c, _d;
        var _ref5 = event.targetTouches ? event.targetTouches[0] : event,
          clientX = _ref5.clientX,
          clientY = _ref5.clientY;
        var deltaX = -(clientX - ((_b = (_a = _this3.touchStart) === null || _a === void 0 ? void 0 : _a.x) !== null && _b !== void 0 ? _b : 0)) * _this3.touchMultiplier;
        var deltaY = -(clientY - ((_d = (_c = _this3.touchStart) === null || _c === void 0 ? void 0 : _c.y) !== null && _d !== void 0 ? _d : 0)) * _this3.touchMultiplier;
        _this3.touchStart.x = clientX;
        _this3.touchStart.y = clientY;
        _this3.lastDelta = {
          x: deltaX,
          y: deltaY
        };
        _this3.emitter.emit('scroll', {
          deltaX: deltaX,
          deltaY: deltaY,
          event: event
        });
      };
      this.onTouchEnd = function (event) {
        _this3.emitter.emit('scroll', {
          deltaX: _this3.lastDelta.x,
          deltaY: _this3.lastDelta.y,
          event: event
        });
      };
      this.onWheel = function (event) {
        var deltaX = event.deltaX,
          deltaY = event.deltaY,
          deltaMode = event.deltaMode;
        var multiplierX = deltaMode === 1 ? LINE_HEIGHT : deltaMode === 2 ? _this3.windowWidth : 1;
        var multiplierY = deltaMode === 1 ? LINE_HEIGHT : deltaMode === 2 ? _this3.windowHeight : 1;
        deltaX *= multiplierX;
        deltaY *= multiplierY;
        deltaX *= _this3.wheelMultiplier;
        deltaY *= _this3.wheelMultiplier;
        _this3.emitter.emit('scroll', {
          deltaX: deltaX,
          deltaY: deltaY,
          event: event
        });
      };
      this.onWindowResize = function () {
        _this3.windowWidth = window.innerWidth;
        _this3.windowHeight = window.innerHeight;
      };
      this.element = element;
      this.wheelMultiplier = wheelMultiplier;
      this.touchMultiplier = touchMultiplier;
      this.touchStart = {
        x: null,
        y: null
      };
      this.emitter = new Emitter();
      window.addEventListener('resize', this.onWindowResize, false);
      this.onWindowResize();
      this.element.addEventListener('wheel', this.onWheel, {
        passive: false
      });
      this.element.addEventListener('touchstart', this.onTouchStart, {
        passive: false
      });
      this.element.addEventListener('touchmove', this.onTouchMove, {
        passive: false
      });
      this.element.addEventListener('touchend', this.onTouchEnd, {
        passive: false
      });
    }
    _createClass(VirtualScroll, [{
      key: "on",
      value: function on(event, callback) {
        return this.emitter.on(event, callback);
      }
    }, {
      key: "destroy",
      value: function destroy() {
        this.emitter.destroy();
        window.removeEventListener('resize', this.onWindowResize, false);
        this.element.removeEventListener('wheel', this.onWheel);
        this.element.removeEventListener('touchstart', this.onTouchStart);
        this.element.removeEventListener('touchmove', this.onTouchMove);
        this.element.removeEventListener('touchend', this.onTouchEnd);
      }
    }]);
    return VirtualScroll;
  }();
  var Lenis = /*#__PURE__*/function () {
    function Lenis() {
      var _this4 = this;
      var _ref6 = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
        _ref6$wrapper = _ref6.wrapper,
        wrapper = _ref6$wrapper === void 0 ? window : _ref6$wrapper,
        _ref6$content = _ref6.content,
        content = _ref6$content === void 0 ? document.documentElement : _ref6$content,
        _ref6$wheelEventsTarg = _ref6.wheelEventsTarget,
        wheelEventsTarget = _ref6$wheelEventsTarg === void 0 ? wrapper : _ref6$wheelEventsTarg,
        _ref6$eventsTarget = _ref6.eventsTarget,
        eventsTarget = _ref6$eventsTarget === void 0 ? wheelEventsTarget : _ref6$eventsTarget,
        _ref6$smoothWheel = _ref6.smoothWheel,
        smoothWheel = _ref6$smoothWheel === void 0 ? true : _ref6$smoothWheel,
        _ref6$syncTouch = _ref6.syncTouch,
        syncTouch = _ref6$syncTouch === void 0 ? false : _ref6$syncTouch,
        _ref6$syncTouchLerp = _ref6.syncTouchLerp,
        syncTouchLerp = _ref6$syncTouchLerp === void 0 ? 0.075 : _ref6$syncTouchLerp,
        _ref6$touchInertiaMul = _ref6.touchInertiaMultiplier,
        touchInertiaMultiplier = _ref6$touchInertiaMul === void 0 ? 35 : _ref6$touchInertiaMul,
        duration = _ref6.duration,
        _ref6$easing = _ref6.easing,
        easing = _ref6$easing === void 0 ? function (t) {
          return Math.min(1, 1.001 - Math.pow(2, -10 * t));
        } : _ref6$easing,
        _ref6$lerp = _ref6.lerp,
        lerp = _ref6$lerp === void 0 ? 0.1 : _ref6$lerp,
        _ref6$infinite = _ref6.infinite,
        infinite = _ref6$infinite === void 0 ? false : _ref6$infinite,
        _ref6$orientation = _ref6.orientation,
        orientation = _ref6$orientation === void 0 ? 'vertical' : _ref6$orientation,
        _ref6$gestureOrientat = _ref6.gestureOrientation,
        gestureOrientation = _ref6$gestureOrientat === void 0 ? 'vertical' : _ref6$gestureOrientat,
        _ref6$touchMultiplier = _ref6.touchMultiplier,
        touchMultiplier = _ref6$touchMultiplier === void 0 ? 1 : _ref6$touchMultiplier,
        _ref6$wheelMultiplier = _ref6.wheelMultiplier,
        wheelMultiplier = _ref6$wheelMultiplier === void 0 ? 1 : _ref6$wheelMultiplier,
        _ref6$autoResize = _ref6.autoResize,
        autoResize = _ref6$autoResize === void 0 ? true : _ref6$autoResize,
        prevent = _ref6.prevent,
        virtualScroll = _ref6.virtualScroll,
        _ref6$__experimental_ = _ref6.__experimental__naiveDimensions,
        __experimental__naiveDimensions = _ref6$__experimental_ === void 0 ? false : _ref6$__experimental_;
      _classCallCheck(this, Lenis);
      this.__isScrolling = false;
      this.__isStopped = false;
      this.__isLocked = false;
      this.userData = {};
      this.lastVelocity = 0;
      this.velocity = 0;
      this.direction = 0;
      this.onPointerDown = function (event) {
        if (event.button === 1) {
          _this4.reset();
        }
      };
      this.onVirtualScroll = function (data) {
        if (typeof _this4.options.virtualScroll === 'function' && _this4.options.virtualScroll(data) === false) return;
        var deltaX = data.deltaX,
          deltaY = data.deltaY,
          event = data.event;
        _this4.emitter.emit('virtual-scroll', {
          deltaX: deltaX,
          deltaY: deltaY,
          event: event
        });
        if (event.ctrlKey) return;
        var isTouch = event.type.includes('touch');
        var isWheel = event.type.includes('wheel');
        _this4.isTouching = event.type === 'touchstart' || event.type === 'touchmove';
        var isTapToStop = _this4.options.syncTouch && isTouch && event.type === 'touchstart' && !_this4.isStopped && !_this4.isLocked;
        if (isTapToStop) {
          _this4.reset();
          return;
        }
        var isClick = deltaX === 0 && deltaY === 0;
        var isUnknownGesture = _this4.options.gestureOrientation === 'vertical' && deltaY === 0 || _this4.options.gestureOrientation === 'horizontal' && deltaX === 0;
        if (isClick || isUnknownGesture) {
          return;
        }
        var composedPath = event.composedPath();
        composedPath = composedPath.slice(0, composedPath.indexOf(_this4.rootElement));
        var prevent = _this4.options.prevent;
        if (!!composedPath.find(function (node) {
          var _a, _b, _c, _d, _e;
          return node instanceof Element && (typeof prevent === 'function' && (prevent === null || prevent === void 0 ? void 0 : prevent(node)) || ((_a = node.hasAttribute) === null || _a === void 0 ? void 0 : _a.call(node, 'data-lenis-prevent')) || isTouch && ((_b = node.hasAttribute) === null || _b === void 0 ? void 0 : _b.call(node, 'data-lenis-prevent-touch')) || isWheel && ((_c = node.hasAttribute) === null || _c === void 0 ? void 0 : _c.call(node, 'data-lenis-prevent-wheel')) || ((_d = node.classList) === null || _d === void 0 ? void 0 : _d.contains('lenis')) && !((_e = node.classList) === null || _e === void 0 ? void 0 : _e.contains('lenis-stopped')));
        })) return;
        if (_this4.isStopped || _this4.isLocked) {
          event.preventDefault();
          return;
        }
        var isSmooth = _this4.options.syncTouch && isTouch || _this4.options.smoothWheel && isWheel;
        if (!isSmooth) {
          _this4.isScrolling = 'native';
          _this4.animate.stop();
          return;
        }
        event.preventDefault();
        var delta = deltaY;
        if (_this4.options.gestureOrientation === 'both') {
          delta = Math.abs(deltaY) > Math.abs(deltaX) ? deltaY : deltaX;
        } else if (_this4.options.gestureOrientation === 'horizontal') {
          delta = deltaX;
        }
        var syncTouch = isTouch && _this4.options.syncTouch;
        var isTouchEnd = isTouch && event.type === 'touchend';
        var hasTouchInertia = isTouchEnd && Math.abs(delta) > 5;
        if (hasTouchInertia) {
          delta = _this4.velocity * _this4.options.touchInertiaMultiplier;
        }
        _this4.scrollTo(_this4.targetScroll + delta, Object.assign({
          programmatic: false
        }, syncTouch ? {
          lerp: hasTouchInertia ? _this4.options.syncTouchLerp : 1
        } : {
          lerp: _this4.options.lerp,
          duration: _this4.options.duration,
          easing: _this4.options.easing
        }));
      };
      this.onNativeScroll = function () {
        clearTimeout(_this4.__resetVelocityTimeout);
        delete _this4.__resetVelocityTimeout;
        if (_this4.__preventNextNativeScrollEvent) {
          delete _this4.__preventNextNativeScrollEvent;
          return;
        }
        if (_this4.isScrolling === false || _this4.isScrolling === 'native') {
          var lastScroll = _this4.animatedScroll;
          _this4.animatedScroll = _this4.targetScroll = _this4.actualScroll;
          _this4.lastVelocity = _this4.velocity;
          _this4.velocity = _this4.animatedScroll - lastScroll;
          _this4.direction = Math.sign(_this4.animatedScroll - lastScroll);
          _this4.isScrolling = 'native';
          _this4.emit();
          if (_this4.velocity !== 0) {
            _this4.__resetVelocityTimeout = setTimeout(function () {
              _this4.lastVelocity = _this4.velocity;
              _this4.velocity = 0;
              _this4.isScrolling = false;
              _this4.emit();
            }, 400);
          }
        }
      };
      window.lenisVersion = version;
      if (!wrapper || wrapper === document.documentElement || wrapper === document.body) {
        wrapper = window;
      }
      this.options = {
        wrapper: wrapper,
        content: content,
        wheelEventsTarget: wheelEventsTarget,
        eventsTarget: eventsTarget,
        smoothWheel: smoothWheel,
        syncTouch: syncTouch,
        syncTouchLerp: syncTouchLerp,
        touchInertiaMultiplier: touchInertiaMultiplier,
        duration: duration,
        easing: easing,
        lerp: lerp,
        infinite: infinite,
        gestureOrientation: gestureOrientation,
        orientation: orientation,
        touchMultiplier: touchMultiplier,
        wheelMultiplier: wheelMultiplier,
        autoResize: autoResize,
        prevent: prevent,
        virtualScroll: virtualScroll,
        __experimental__naiveDimensions: __experimental__naiveDimensions
      };
      this.animate = new Animate();
      this.emitter = new Emitter();
      this.dimensions = new Dimensions({
        wrapper: wrapper,
        content: content,
        autoResize: autoResize
      });
      this.updateClassName();
      this.userData = {};
      this.time = 0;
      this.velocity = this.lastVelocity = 0;
      this.isLocked = false;
      this.isStopped = false;
      this.isScrolling = false;
      this.targetScroll = this.animatedScroll = this.actualScroll;
      this.options.wrapper.addEventListener('scroll', this.onNativeScroll, false);
      this.options.wrapper.addEventListener('pointerdown', this.onPointerDown, false);
      this.virtualScroll = new VirtualScroll(eventsTarget, {
        touchMultiplier: touchMultiplier,
        wheelMultiplier: wheelMultiplier
      });
      this.virtualScroll.on('scroll', this.onVirtualScroll);
    }
    _createClass(Lenis, [{
      key: "destroy",
      value: function destroy() {
        this.emitter.destroy();
        this.options.wrapper.removeEventListener('scroll', this.onNativeScroll, false);
        this.options.wrapper.removeEventListener('pointerdown', this.onPointerDown, false);
        this.virtualScroll.destroy();
        this.dimensions.destroy();
        this.cleanUpClassName();
      }
    }, {
      key: "on",
      value: function on(event, callback) {
        return this.emitter.on(event, callback);
      }
    }, {
      key: "off",
      value: function off(event, callback) {
        return this.emitter.off(event, callback);
      }
    }, {
      key: "setScroll",
      value: function setScroll(scroll) {
        if (this.isHorizontal) {
          this.rootElement.scrollLeft = scroll;
        } else {
          this.rootElement.scrollTop = scroll;
        }
      }
    }, {
      key: "resize",
      value: function resize() {
        this.dimensions.resize();
      }
    }, {
      key: "emit",
      value: function emit() {
        this.emitter.emit('scroll', this);
      }
    }, {
      key: "reset",
      value: function reset() {
        this.isLocked = false;
        this.isScrolling = false;
        this.animatedScroll = this.targetScroll = this.actualScroll;
        this.lastVelocity = this.velocity = 0;
        this.animate.stop();
      }
    }, {
      key: "start",
      value: function start() {
        if (!this.isStopped) return;
        this.isStopped = false;
        this.reset();
      }
    }, {
      key: "stop",
      value: function stop() {
        if (this.isStopped) return;
        this.isStopped = true;
        this.animate.stop();
        this.reset();
      }
    }, {
      key: "raf",
      value: function raf(time) {
        var deltaTime = time - (this.time || time);
        this.time = time;
        this.animate.advance(deltaTime * 0.001);
      }
    }, {
      key: "scrollTo",
      value: function scrollTo(target) {
        var _this5 = this;
        var _ref7 = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {},
          _ref7$offset = _ref7.offset,
          offset = _ref7$offset === void 0 ? 0 : _ref7$offset,
          _ref7$immediate = _ref7.immediate,
          immediate = _ref7$immediate === void 0 ? false : _ref7$immediate,
          _ref7$lock = _ref7.lock,
          lock = _ref7$lock === void 0 ? false : _ref7$lock,
          _ref7$duration = _ref7.duration,
          duration = _ref7$duration === void 0 ? this.options.duration : _ref7$duration,
          _ref7$easing = _ref7.easing,
          easing = _ref7$easing === void 0 ? this.options.easing : _ref7$easing,
          _ref7$lerp = _ref7.lerp,
          lerp = _ref7$lerp === void 0 ? this.options.lerp : _ref7$lerp,
          _onStart = _ref7.onStart,
          onComplete = _ref7.onComplete,
          _ref7$force = _ref7.force,
          force = _ref7$force === void 0 ? false : _ref7$force,
          _ref7$programmatic = _ref7.programmatic,
          programmatic = _ref7$programmatic === void 0 ? true : _ref7$programmatic,
          _ref7$userData = _ref7.userData,
          userData = _ref7$userData === void 0 ? {} : _ref7$userData;
        if ((this.isStopped || this.isLocked) && !force) return;
        if (typeof target === 'string' && ['top', 'left', 'start'].includes(target)) {
          target = 0;
        } else if (typeof target === 'string' && ['bottom', 'right', 'end'].includes(target)) {
          target = this.limit;
        } else {
          var node;
          if (typeof target === 'string') {
            node = document.querySelector(target);
          } else if (target instanceof HTMLElement && (target === null || target === void 0 ? void 0 : target.nodeType)) {
            node = target;
          }
          if (node) {
            if (this.options.wrapper !== window) {
              var wrapperRect = this.rootElement.getBoundingClientRect();
              offset -= this.isHorizontal ? wrapperRect.left : wrapperRect.top;
            }
            var rect = node.getBoundingClientRect();
            target = (this.isHorizontal ? rect.left : rect.top) + this.animatedScroll;
          }
        }
        if (typeof target !== 'number') return;
        target += offset;
        target = Math.round(target);
        if (this.options.infinite) {
          if (programmatic) {
            this.targetScroll = this.animatedScroll = this.scroll;
          }
        } else {
          target = clamp(0, target, this.limit);
        }
        if (target === this.targetScroll) return;
        this.userData = userData;
        if (immediate) {
          this.animatedScroll = this.targetScroll = target;
          this.setScroll(this.scroll);
          this.reset();
          this.preventNextNativeScrollEvent();
          this.emit();
          onComplete === null || onComplete === void 0 ? void 0 : onComplete(this);
          this.userData = {};
          return;
        }
        if (!programmatic) {
          this.targetScroll = target;
        }
        this.animate.fromTo(this.animatedScroll, target, {
          duration: duration,
          easing: easing,
          lerp: lerp,
          onStart: function onStart() {
            if (lock) _this5.isLocked = true;
            _this5.isScrolling = 'smooth';
            _onStart === null || _onStart === void 0 ? void 0 : _onStart(_this5);
          },
          onUpdate: function onUpdate(value, completed) {
            _this5.isScrolling = 'smooth';
            _this5.lastVelocity = _this5.velocity;
            _this5.velocity = value - _this5.animatedScroll;
            _this5.direction = Math.sign(_this5.velocity);
            _this5.animatedScroll = value;
            _this5.setScroll(_this5.scroll);
            if (programmatic) {
              _this5.targetScroll = value;
            }
            if (!completed) _this5.emit();
            if (completed) {
              _this5.reset();
              _this5.emit();
              onComplete === null || onComplete === void 0 ? void 0 : onComplete(_this5);
              _this5.userData = {};
              _this5.preventNextNativeScrollEvent();
            }
          }
        });
      }
    }, {
      key: "preventNextNativeScrollEvent",
      value: function preventNextNativeScrollEvent() {
        var _this6 = this;
        this.__preventNextNativeScrollEvent = true;
        requestAnimationFrame(function () {
          delete _this6.__preventNextNativeScrollEvent;
        });
      }
    }, {
      key: "rootElement",
      get: function get() {
        return this.options.wrapper === window ? document.documentElement : this.options.wrapper;
      }
    }, {
      key: "limit",
      get: function get() {
        if (this.options.__experimental__naiveDimensions) {
          if (this.isHorizontal) {
            return this.rootElement.scrollWidth - this.rootElement.clientWidth;
          } else {
            return this.rootElement.scrollHeight - this.rootElement.clientHeight;
          }
        } else {
          return this.dimensions.limit[this.isHorizontal ? 'x' : 'y'];
        }
      }
    }, {
      key: "isHorizontal",
      get: function get() {
        return this.options.orientation === 'horizontal';
      }
    }, {
      key: "actualScroll",
      get: function get() {
        return this.isHorizontal ? this.rootElement.scrollLeft : this.rootElement.scrollTop;
      }
    }, {
      key: "scroll",
      get: function get() {
        return this.options.infinite ? modulo(this.animatedScroll, this.limit) : this.animatedScroll;
      }
    }, {
      key: "progress",
      get: function get() {
        return this.limit === 0 ? 1 : this.scroll / this.limit;
      }
    }, {
      key: "isScrolling",
      get: function get() {
        return this.__isScrolling;
      },
      set: function set(value) {
        if (this.__isScrolling !== value) {
          this.__isScrolling = value;
          this.updateClassName();
        }
      }
    }, {
      key: "isStopped",
      get: function get() {
        return this.__isStopped;
      },
      set: function set(value) {
        if (this.__isStopped !== value) {
          this.__isStopped = value;
          this.updateClassName();
        }
      }
    }, {
      key: "isLocked",
      get: function get() {
        return this.__isLocked;
      },
      set: function set(value) {
        if (this.__isLocked !== value) {
          this.__isLocked = value;
          this.updateClassName();
        }
      }
    }, {
      key: "isSmooth",
      get: function get() {
        return this.isScrolling === 'smooth';
      }
    }, {
      key: "className",
      get: function get() {
        var className = 'lenis';
        if (this.isStopped) className += ' lenis-stopped';
        if (this.isLocked) className += ' lenis-locked';
        if (this.isScrolling) className += ' lenis-scrolling';
        if (this.isScrolling === 'smooth') className += ' lenis-smooth';
        return className;
      }
    }, {
      key: "updateClassName",
      value: function updateClassName() {
        this.cleanUpClassName();
        this.rootElement.className = "".concat(this.rootElement.className, " ").concat(this.className).trim();
      }
    }, {
      key: "cleanUpClassName",
      value: function cleanUpClassName() {
        this.rootElement.className = this.rootElement.className.replace(/lenis(-\w+)?/g, '').trim();
      }
    }]);
    return Lenis;
  }();
  return Lenis;
});
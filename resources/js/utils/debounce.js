/**
 * @see https://github.com/alpinejs/alpine/blob/4654eb023e760920fa957d7b3e43a599cb88023d/packages/alpinejs/src/utils/debounce.js
 */
export default function debounce(func, wait) {
    var timeout

    return function () {
        var context = this, args = arguments

        var later = function () {
            timeout = null

            func.apply(context, args)
        }

        clearTimeout(timeout)

        timeout = setTimeout(later, wait)
    }
}

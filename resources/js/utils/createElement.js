/**
 * @see https://github.com/alpinejs/alpine/pull/2529
 */
export default function createElement(html) {
    /**
     * Using the "simplified" version here as we do not need to
     * morph a fully fledged page nor table.
     */
    return document.createRange().createContextualFragment(html);
}
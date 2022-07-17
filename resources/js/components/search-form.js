import ajax from '../utils/ajax';
import createElement from '../utils/createElement';

export default (el) => {
    const ERROR_ID = el.dataset.errorWrapperId;
    const RESULT_ID = el.dataset.resultsId;

    const input = el.querySelector('#search');
    const dialog = document.querySelector('dialog');

    attachMovieCardActions();
    hijackNavigationLinks();

    el.addEventListener('submit', (e) => {
        e.preventDefault();

        search();
    });

    el.addEventListener('input', () => {
        if (input.value.length < 2) {
            return;
        }

        search();
    });

    function search() {
        get('/', new FormData(el));
    }

    function get(url = null, data = null) {
        // let loading = true;

        ajax.get(url, data).then(onSuccessfullRequest).catch(onFailedRequest);
    }

    function attachMovieCardActions() {
        document.querySelectorAll('.movie-card > button').forEach((btn) => {
            btn.addEventListener('click', () => {
                const parent = btn.parentElement.cloneNode(true);

                parent.querySelector('button').remove();

                dialog.innerHTML = parent.outerHTML;

                dialog.showModal();
            });
        });
    }

    function hijackNavigationLinks() {
        document.querySelectorAll('.movie-pagination-wrapper a').forEach((anchor) => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();

                get(e.target.href);
            }, { once: true });
        });
    }

    function onSuccessfullRequest(response) {
        const fragment = createElement(response.data);

        [ERROR_ID, RESULT_ID].forEach((ID) => {
            const element = document.querySelector(ID);
            const target = fragment.querySelector(ID);

            if (!element || !target) {
                return;
            }

            element.replaceWith(target);
        });

        queueMicrotask(attachMovieCardActions);
        queueMicrotask(hijackNavigationLinks);
    }

    function onFailedRequest(error) {
        const element = document.querySelector(RESULT_ID);

        if (!element) {
            return;
        }

        const iframe = document.createElement('iframe');

        iframe.srcdoc = error.data;

        Object.assign(iframe.style, {
            width: '100%',
            minHeight: '100vh',
            border: 'none',
            borderRadius: 'var(--rounded)',
        });

        element.innerHTML = '';

        element.appendChild(iframe);
    }
};

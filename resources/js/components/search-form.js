import ajax from '../utils/ajax';
import createElement from '../utils/createElement';

export default (el) => {
    const ERROR_ID = el.dataset.errorWrapperId;
    const RESULT_ID = el.dataset.resultsId;

    const input = el.querySelector('#search');

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
        ajax.get('/', new FormData(el)).then(onSuccessfullRequest).catch(onFailedRequest);
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
    }

    function onFailedRequest(error) {
        console.error(error);
    }
};

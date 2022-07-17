const METHODS = {
    GET: 'GET',
    // POST: 'POST',
    // PUT: 'PUT',
    // PATCH: 'PATCH',
    // DELETE: 'DELETE',
};

class Ajax {
    /**
     * @param {string|URL} url
     * @param {FormData|Object|null} data 
     * @returns {Promise}
     */
    get(url, data = null) {
        return this.go(url, { method: METHODS.GET, data });
    }

    /**
     * @private 
     */
    async go(url, options = {}) {
        const opts = {
            method: METHODS.GET,
            data: null,
            headers: {},
            ...options,
        };

        const data = this.makeData(opts);

        const response = await fetch(this.makeRequestUrl(url, opts.method, data), {
            method: opts.method,
            body: opts.method !== METHODS.GET ? data : null,
            headers: this.makeRequestHeaders(opts),
        });

        let responseData;

        if (response.headers.get('Content-Type') === 'application/json') {
            responseData = await response.json();
        } else {
            responseData = await response.text();
        }

        const result = {
            url,
            response,
            options,
            data: responseData,
        };

        this.throwIfResponseNotOk(result);

        return result;
    }

    /* Helpers
    ------------------------------------------------*/

    makeData({ method, data }) {
        if (method === METHODS.GET) {
            return new URLSearchParams(data);
        }

        return data;
    }

    makeRequestUrl(url, method, data) {
        if (method !== METHODS.GET) {
            return url;
        }

        if (!(url instanceof URL)) {
            url = new URL(url, location.origin);
        }

        for (const [key, value] of data.entries()) {
            url.searchParams.set(key, value);
        }

        return url;
    }

    makeRequestHeaders(options) {
        return {
            ...options.headers,
            Accept: 'text/html, application/json, application/xhtml+xml',
            'X-Requested-With': 'XMLHttpRequest',
        };
    }

    makeResponse(response) {
        if (response.ok) {
            return;
        }

        console.error(response);

        throw response;
    }

    throwIfResponseNotOk(result) {
        if (result.response.ok) {
            return;
        }

        console.error(result);

        throw result;
    }
}

export default new Ajax;
/**
 * Internal dependencies.
 */
import loadingBox from '../components/loadingBox';

const add = (message) => {
	loadingBox.remove();

	$(`<p class="ww-error-message">${message}</p>`).insertAfter('.carbon-theme-options h2')
}

const remove = () => $('.ww-error-message').remove();

export default {
	add,
	remove,
};

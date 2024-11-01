/**
 * Internal dependencies.
 */
import messageBox from '../components/messageBox';

const add = (message) => {
	messageBox.remove();

	$('.carbon-theme-options h2').append(`<span class="ww-loading"></span>`);
}

const remove = () => $('.ww-loading').remove();

export default {
	add,
	remove,
};

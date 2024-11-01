/**
 * Internal dependencies.
 */
import auth from './auth';
import messageBox from '../components/messageBox'

const wp_nonce = window.ww_data.ww_ajax_nonce;

const onError = (result) => {
	if (!result.success) {
		messageBox.add('Request failed');
	}

	return result;
}

const PostCredentials = ({ token, user }) => {
	return $.ajax({
		url: ajaxurl,
		method: 'POST',
		data: {
			action: 'ww_save_token',
			token,
			user,
			wp_nonce,
		},
		error() {
			console.log('asdfasdf');
			debugger;
		}
	})
	.then(onError);
}

const PostWidgets = ({ widgets, globalWidgetId }) => {
	return $.ajax({
		url: ajaxurl,
		method: 'POST',
		data: {
			action: 'ww_save_widgets',
			widgets,
			globalWidgetId,
			wp_nonce,
		},
	})
	.then(onError);
}

const PostBubbleWidget = ({ id }) => {
	return $.ajax({
		url: ajaxurl,
		method: 'POST',
		data: {
			action: 'ww_save_bubble_widget',
			id,
			wp_nonce,
		},
	})
	.then(onError);
}

export default {
	PostCredentials,
	PostWidgets,
	PostBubbleWidget,
};

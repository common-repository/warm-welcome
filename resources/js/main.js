
/**
 * Internal dependencies.
 */
import auth from './auth';
import mutations from './graphql/mutations';
import queries from './graphql/queries';
import ajax from './ajax';

import loadingBox from '../components/loadingBox';

$(window).load(function() {
	const loadWidgets = async () => {
		const { data: { user } } = await queries.GetWidgets();

		return await ajax.PostWidgets({
			widgets: JSON.stringify(user.widgets.data),
			globalWidgetId: user.additionalData.globalWidgetId,
		});
	}

	if (window.ww_data.ww_token) {
		loadWidgets();
	}

	$('.ww-log-in').on('click', async function (e)  {
		loadingBox.add();

		const email = $('[name="carbon_fields_compact_input[_ww_email]"]').val();
		const password = $('[name="carbon_fields_compact_input[_ww_password]"]').val();

		const { data: { login: accessToken } } = await mutations.Login(email, password);

		auth.setToken(accessToken, email);

		await loadWidgets().then(({success}) => {
			if (!success) {
				return;
			}

			loadingBox.remove();

			window.location.reload();
		});

		e.preventDefault();
	});

	$('.ww-log-out').on('click', async function (e)  {
		loadingBox.add();

		await ajax.PostCredentials({
			action: 'ww_save_token',
			token: '',
			user: '',
		}).then(({success}) => {
			if (!success) {
				return;
			}

			loadingBox.remove();

			window.location.reload();
		});
	});

	$('.ww-save-options').on('click', async (e) => {
		e.preventDefault();

		loadingBox.add();

		const widgetUniqueId = $('[name="carbon_fields_compact_input[_crb_default_bubble_widget]"]').val();
		const { site_url, widgets } = ww_data.ww_widgets_data;

		if (widgetUniqueId !== 'none') {
			await mutations.UpdateWidgetURL(widgetUniqueId, site_url, true);
		}

		await ajax.PostBubbleWidget({
			id: widgetUniqueId,
		}).then(({success}) => {
			if (!success) {
				return;
			}

			loadingBox.remove();

			window.location.reload();
		});
	})
});


/**
 * Internal dependencies.
 */
import ajax from './ajax';

let accessToken = window.ww_data.ww_token;

export default {
	async isUserLoggedIn() {
		if (!clientProvider.getDefaultClient()) {
			return false;
		}

		return !!(await this.getUser());
	},

	async getUser() {
		try {
			const { data: { user } } = await clientProvider.getDefaultClient().query({
				query: UserQueries.GetUser,
			});

			return user;
		} catch (error) {
			return null;
		}
	},

	async reset() {
		window.client.defaults.headers.Authorization = '';
		window.localStorage.removeItem('accessToken');
		await clientProvider.getDefaultClient().cache.reset();

		// clear caches
		window.FileCache && window.FileCache.clearCache();
		window.ServerDataInitializer.resetServerDataCheckers();
		StoreStateManager.getInstance().resetState();
	},

	async setToken(token, userEmail) {
		accessToken = token;

		await ajax.PostCredentials({
			token: accessToken,
			user: userEmail,
		});
	},

	getToken() {
		return accessToken;
	},

	hasToken() {
		return !!this.getToken();
	},
};

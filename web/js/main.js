let counter = 0;

const usernameMinLength = 3;
const usernameMaxLength = 75;

const passwordMinLength = 7;
const passwordMaxLength = 35;

document.addEventListener('DOMContentLoaded', (event) => {
	const urlSearchParams = new URLSearchParams(window.location.search);
	const urlParams = Object.fromEntries(urlSearchParams.entries());

	if (urlParams.token != undefined && urlParams.token != '') {
		token = urlParams.token;

		getProfile();
	}

	document.querySelector('.js-frm-login').addEventListener("submit", function(e) {
		e.preventDefault();

		showLoader();

		const msgSelector = this.querySelector('.js-msg');
		msgSelector.innerHTML = '';

		const username = this.querySelector('input[name=username]').value;

		if (username == '') {
			showResponseError(['Type username']);
			showLoader(false);
			return;
		}

		if (username.length < usernameMinLength || username.length > usernameMaxLength) {
			showResponseError(['Username length must be ' + usernameMinLength + '-' + usernameMaxLength]);
			showLoader(false);
			return;
		}

		let params = 'username=' + username;

		const password = this.querySelector('input[name=password]').value;

		if (password == '') {
			showResponseError(['Type password']);
			showLoader(false);
			return;
		}

		if (password.length < passwordMinLength || password.length > passwordMaxLength) {
			showResponseError(['Password length must be ' + passwordMinLength + '-' + passwordMaxLength]);
			showLoader(false);
			return;
		}

		params += '&password=' + password;

		fetch(API, {
			method: 'POST',
			headers: {
				'Content-Type':'application/x-www-form-urlencoded'
			},
			body: params
		})
			.then(response => response.json())
			.then(response => {
				const {data, error, status} = response;

				if (status == 'success' && data.token != undefined) {
					location.href = location.pathname + '?token=' + data.token;
				} else {
					console.log('error response');
					showResponseError(error);
				}

				showLoader(false);
			})
			.catch((error) => {
				console.log('error catch');
				showResponseError([error]);
				showLoader(false);
			});
	});

	document.querySelector('.js-btn-exit').addEventListener("click", function (e) {
		e.preventDefault();

		showLoader();

		fetch(API + 'profile/exit/?token=' + token, {
			method: 'PUT',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		})
			.then(response => response.json())
			.then(response => {
				const { data, error, status } = response;

				if (status == 'success') {
					location.href = location.pathname;
				} else {
					if (data.showLogin) {
						location.href = location.pathname;
					} else {
						showResponseError(error);
					}
				}

				showLoader(false);
			})
			.catch((error) => {
				console.log('error catch');
				showResponseError([error]);
				showLoader(false);
			});
	});

	document.querySelector('.js-btn-counter').addEventListener("click", function (e) {
		e.preventDefault();

		showLoader();

		fetch(API + 'profile/counter/?token=' + token, {
			method: 'PUT',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		})
			.then(response => response.json())
			.then(response => {
				const { data, error, status } = response;

				if (status == 'success') {
					counter++;
					document.querySelector('.js-profile .js-counter').innerHTML = counter;
				} else {
					if (data.showLogin) {
						location.href = location.pathname;
					} else {
						showResponseError(error);
					}
				}

				showLoader(false);
			})
			.catch((error) => {
				console.log('error catch');
				showResponseError([error]);
				showLoader(false);
			});
	});
});

function getProfile() {
	showLoader();

	fetch(API + 'profile/?token=' + token, {
		method: 'GET',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded'
		}
	})
		.then(response => response.json())
		.then(response => {
			const { data, error, status } = response;

			if (status == 'success' && data.token != undefined) {
				token = data.token;
				counter = data.counter;
				showProfile();
			} else {
				console.log('error response');

				if (data.showLogin) {
					location.href = location.pathname;
				} else {
					showResponseError(error);
				}
			}

			showLoader(false);
		})
		.catch((error) => {
			console.log('error catch');
			showResponseError([error]);
			showLoader(false);
		});
}

function showLoader(show = true) {
	const loaderBlock = document.querySelector('.js-loader');
	loaderBlock.style.display = show ? 'flex' : 'none';
}

function showLoadingError(show = true) {
	const errorBlock = document.querySelector('.js-loader .error');
	errorBlock.style.display = show ? 'block' : 'none';
}

function showResponseError(error = []) {
	const arr = error.filter(item => item != '');
	let msg = '';

	for (let i in arr) {
		msg += arr[i] + '<br/>';
	}
	
	if (msg != '') {
		document.querySelector('.js-frm-login .js-msg').innerHTML = msg;
	}
}

function showProfile() {
	const profileBlock = document.querySelector('.js-profile');
	profileBlock.querySelector('.js-counter').innerHTML = counter;

	document.querySelector('.js-frm-login').style.display = 'none';
	profileBlock.style.display = 'block';
}

function showLogin() {
	document.querySelector('.js-profile').style.display = 'none';
	document.querySelector('.js-frm-login').style.display = 'block';
}
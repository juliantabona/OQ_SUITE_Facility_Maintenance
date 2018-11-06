class Auth {
    constructor () {
        this.token = null;
        this.user = null;
    }

    login(token, user){        
        console.log('set token and user');
        window.localStorage.setItem('token', token);
        window.localStorage.setItem('user', JSON.stringify(user));

        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

        this.token = token;
        this.user = user;

        Event.$emit('userLoggedIn');

        return (this.token && this.user) ? true : false;

    }

    logout(){        
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        this.token = null;
        this.user = null;

        Event.$emit('userLoggedOut');

    }

    check(){
        //return !! this.token;
        return this.login(localStorage.getItem('token'), JSON.parse(localStorage.getItem('user')));
    }
}

export default new Auth();
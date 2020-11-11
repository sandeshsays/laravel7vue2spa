export default class Gate {

    constructor(user) {
        this.user = user;
    }

    isSuperadmin(){
        return this.user.type === 'superadmin';
    }

    isAdmin(){
        return this.user.type === 'admin';
    }

    isUser(){
        return this.user.type === 'user';
    }

    isSuperadminOrAdmin(){
        if(this.user.type==='admin' || this.user.type === 'superadmin'){
            return true;
        }
    }

}

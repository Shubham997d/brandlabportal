export default {


    timeFormat(duration, userId = null) {

        var task_duration;

        if (Array.isArray(duration)) {
            if (duration != undefined) {
                for (var i = 0, len = duration.length; i < len; i++) {
                    if (duration[i]['user_id'] == userId || duration[i]['user_id'] == userId) {
                        task_duration = duration[i]['duration']
                        break;
                    }
                }

            } else {
                task_duration = 0
            }
        }
        else {
            if (duration == undefined) { task_duration = 0 } else { task_duration = duration }
        }
        var sec_num = parseInt(task_duration)
        var hours = Math.floor(sec_num / 3600)
        var minutes = Math.floor(sec_num / 60) % 60
        var string = '';
        if (hours) {
            if (hours == 1) {
                string = string + hours + ' hour'
            } else {
                string = string + hours + ' hours'
            }
        }
        if (minutes) {
            if (hours) {
                string = string + ' and ' + minutes + ' mins'
            } else {
                string = string + minutes + ' mins'
            }

        }
        if (string == '' || string == undefined) {
            string = '0 hours';
        }
        return string
    },


    checkIfUserHasPermission(array, roleId, hasPermission = false) {
        hasPermission = hasPermission === true ? true : false; // Do Not Check       
        if (hasPermission === false) {
            if (array.includes(roleId)) {
                hasPermission = true;
            } else {
                hasPermission = false;
            }
        }
        return hasPermission;
    },

    checkIfUserHasPermissionToViewButtons(array, action, resource, hasPermission = false) {
        hasPermission = hasPermission === true ? true : false; // Do Not Check       
        if (hasPermission === false) {
            for (var i = 0; i < array.length; i++) {
                if (typeof array[i]['action'] != 'undefined' && typeof array[i]['resource'] != 'undefined' && array[i]['action'] == action && array[i]['resource'] == resource) {
                    hasPermission = true
                }
            }
        }
        return hasPermission;
    },


    setDateFormat(oldDate) {
        var newDate = '';
        if (typeof oldDate != 'undefined' && oldDate != null) {
            try {
                newDate = window.luxon.DateTime.fromISO(oldDate.toISOString()).toISODate();
            }
            catch (err) {
                newDate = window.luxon.DateTime.fromISO(oldDate).toISODate();
            }
            return (typeof newDate != 'undefined' && newDate != null && newDate != '') ? newDate : oldDate;

        } else {
            return null
        }
    },

    setTimeCorrect(arr) {
        if (arr.length != 0) {
            arr = arr.split(":");
            if (arr[1] == "mm") {
                arr[1] = "00";
            }
            if (arr[0] == "HH") {
                arr[0] = "00";
            }
            arr = arr.join(":");
        } else {
            arr = "00:00"
        }

        return arr;
    },

    findIndexOfMultidimensionalArray(array, searchFor, searchBy) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][searchBy] === searchFor) {
                return i;
            }
        }
        return false
    },

    checkIfNotificationsShouldLoad(notificationsParam, state) {
        var shouldSendRequest = false
        if (state.notifications.nextPage > state.notifications.lastPage) { shouldSendRequest = false } else { shouldSendRequest = true }
        if (notificationsParam.loadMore === false) { shouldSendRequest = true }
        return shouldSendRequest
    },

    sanitizeString(str) {
        if (!str) return str;
        str = str.replace(/<p><br[\/]?><[\/]?p>/g, "");
        return str.replace(/<li><br[\/]?><[\/]?li>/g, "");
    },

    getParticularQueryParam(url, param) {
        const params = new URL(url).searchParams;
        return params.get(param);
    },

    scrollToBottomOfElement(id, duration = 200) {
        setTimeout(() => {
            let element = document.getElementById(id);
            element ? element.scrollIntoView(false) : null;
        }, duration);
    },

    hasWhiteSpace(s) {
        return (/\s/).test(s);
    },

    getLastCharacter(s) {
        return s ? s.charAt(s.length - 1) : null;
    },

    getDate(createdAt, formattedDate = true, todayString = false) {
        if (typeof createdAt === 'undefined' || createdAt === null) {
            return false;
        }
        if (todayString === true) {
            let messageDayTimestamp = this.getDate(createdAt, false).ts;
            let now = new Date();
            let startOfDay = new Date(
                now.getFullYear(),
                now.getMonth(),
                now.getDate()
            );
            let currentDayTimestamp = startOfDay / 1000;
            currentDayTimestamp = currentDayTimestamp + "000";
            if (messageDayTimestamp === parseInt(currentDayTimestamp)) {
                return "Today";
            }
        }
        return formattedDate == true
            ? luxon.DateTime.fromISO(createdAt).toLocaleString(
                luxon.DateTime.DATE_MED_WITH_WEEKDAY
            )
            : (todayString === true) ? luxon.DateTime.fromISO(createdAt).startOf("day") : luxon.DateTime.fromISO(createdAt);
    },

    getTime(createdAt) {
        return luxon.DateTime.fromISO(createdAt)
            .setZone("local")
            .toLocaleString(luxon.DateTime.TIME_SIMPLE);
    },

    compareLuxonDates(obj) {
        return this.getDate(createdAt, false).ts;
    },

    assignObject(obj, deleteObjProperty) {
        var object = Object.assign({}, obj);
        delete object[deleteObjProperty];
        return object;
    }



}



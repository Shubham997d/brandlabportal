<?php
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Base\Notifications\DynamicNotification;
use Illuminate\Support\Facades\Notification;
// use NumberFormatter;

if (! function_exists('application_last_updated')) {
    /**
     * Return the date when the application was last updated.
     *
     * @return string
     */
    function application_last_updated()
    {
        return date('F d Y', filemtime(base_path() . '/config/app.php'));
    }
}

if (! function_exists('get_locale')) {
    /**
     * Get user set locale or default locale. not using it now
     *
     * @return string
     */
    function get_locale(): ?string
    {
        $user = auth()->user();

        return $user->lang ?? 'en';
    }
}

if (! function_exists('localize')) {
    /**
     * Translate the given message.
     *
     * @param  string                                                         $key
     * @param  array                                                          $replace
     * @param  string                                                         $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    function localize($key = null, $replace = [], $locale = null)
    {
        $result = trans($key, $replace, $locale);

        if ($result === $key) {
            preg_match('/^.+\.(.+)/', $key, $matches);

            return $matches[1];
        }

        return $result;
    }
}

if (! function_exists('trim_last_dot')) {
    /**
     * Remove any trailing dot (".") character.
     *
     * @param  string $text
     * @return string
     */
    function trim_last_dot($text)
    {
        return substr_replace($text, '', -1);
    }
}





if (! function_exists('secondsToTime')) {
    /**
     * get time format from timestamp
     *
     * @param  string $text
     * @return string
     */
    function secondsToTime($seconds_time,$only_hours)
    {
            $hours = floor($seconds_time / 3600);
            $minutes = floor(($seconds_time - $hours * 3600) / 60);

            if ($only_hours == false) {

                    $string = '';     
                    if ($hours) {
                        if ($hours == 1) {
                            $string .= $hours.' hour';
                        }else{
                            $string .= $hours.' hours';
                        }
                    }
                    if ($minutes) {
                        if ($hours) {
                                $string .= ' and '.$minutes.' mins';                   
                        }else{
                                $string .= $minutes.' mins';
                        }

                    }
                    if (empty($string)) {
                        $string = '0 hours';
                    }

                    return $string;
          }else{

              if (!empty($hours) || !empty($minutes)) {
                    if ($minutes > 30) {
                        $hours = $hours + 1;
                    }
                    return intval($hours);
               }
              else{
                    $hours = 0;
                    return intval($hours);
               }

          }

     }

if (! function_exists('secondsToWords')) {

     function secondsToWords($seconds,$only_days)
    {
        $ret = "";

        /*** get the days ***/


        if ($only_days == true) {
            
            $days = intval(intval($seconds) / (3600*24));

            if (empty($days)) { $days = 0; }
        
            /*** get the hours ***/
            $hours = (intval($seconds) / 3600) % 24;
            if($hours > 12){
                $days = $days+1;
            }
            $ret = $days;
        }
        else {

            $days = intval(intval($seconds) / (3600*24));
            if($days> 0)
            {
                $ret .= "$days days ";
            }

            $hours = (intval($seconds) / 3600) % 24;
            if($hours > 0)
            {
                $ret .= "$hours hours ";
            }
            
            /*** get the minutes ***/
            $minutes = (intval($seconds) / 60) % 60;
            if($minutes > 0)
            {
                $ret .= "$minutes minutes ";
            }

            /*** get the seconds ***/
            $seconds = intval($seconds) % 60;
            if ($seconds > 0) {
                $ret .= "$seconds seconds";
            }

        }

        return $ret;
    }
}



if (! function_exists('getHourlySalaryFormAnnualSalary')) {

    
    function getHourlySalaryFormAnnualSalary($annual_salary)
    {    
        $divide_by = 1950; // number provided by client to calculate hourly salary
        if ($annual_salary > 0) {
            $hourly_salary = $annual_salary/$divide_by;
            $hourly_salary = round(floatval($hourly_salary), 2);
        }else{
            $hourly_salary = 0;
        }
        return $hourly_salary;
    }
}


if (! function_exists('closestMultiple')) {

    
    function closestMultiple($n, $x)
    {    
        $n = (int) $n;
        $x = (int) $x;
        $n = $n + $x / 2;
        $n = $n - ($n % $x);            
        return $n;
    }
}


if (! function_exists('mapArray')) {

function mapArray(array $array, $keys,$defaultVal = 'Not Available' )
{
    if (!is_array($keys)) {$keys = [$keys];}
    return array_map(function ($el) use ($keys,$defaultVal) {
        $o = [];
        foreach($keys as $key){
            //  if(isset($el[$key]))$o[$key] = $el[$key]; //you can do it this way if you don't want to set a default for missing keys.
            $o[$key] = isset($el[$key])?$el[$key]: $defaultVal;
        }
        return $o;
    }, $array);
}

}

if (! function_exists('checkIfUserHasPermission')) {
    
    function checkIfUserHasPermission(array $array, $roleId,$hasPermission = false)
    {
        $hasPermission = ($hasPermission === true) ? true : false; // Do Not Check
       
        if ($hasPermission === false) {
            if (in_array($roleId, $array['roleIds'])){
                $hasPermission = true;
            }else{
                $hasPermission = false;
            }            
        }
        return $hasPermission;
    }
    
}

if (! function_exists('getNumberOfParticularLength')) {

    function getNumberOfParticularLength($length,$last_digit = 0) 
    {
        $result = 1;

        for($i = 0; $i < $length; $i++) {
            $result .= $last_digit;
        }
    
        return $result;
    }
    
}


if (! function_exists('formatCurrencyAccordingToDefault')) {

    function formatCurrencyAccordingToDefault($amount) 
    {  
        return (float) number_format((float)$amount, 2, '.', '');
        // $formatter = new NumberFormatter('en_GB',  NumberFormatter::CURRENCY);
        // return $formatter->formatCurrency((int) $amount, config('constants.salespipeline.miscellaneous.currency.default.code'));
    }
    
}

if (! function_exists('formatString')) {
     /**
     * Mostly used in salespipeline admin reports
     *
     * @return string
     */

    function formatString($toFormatString,$withDefaultCurrencySymbol = false, $inverseFormat = false, $slugFormat = false) 
    {  
        $string = '';
        $string .= ($withDefaultCurrencySymbol === true) ? '('.config('constants.salespipeline.miscellaneous.currency.default.symbol').') ' : $string;
        $string .= ($inverseFormat === false) ? trim(ucfirst(str_replace('_',' ',$toFormatString))) : trim(strtolower(str_replace(' ','_',$toFormatString)));
        $string = ($slugFormat === true) ? trim(strtolower(str_replace(' ','-',$toFormatString))) : $string;
        return $string;
    }
    
}

if (! function_exists('getConvertedValue')) {
    /**
     * Convert the deal value into default currency
     *
     * @return string
     */

    function getConvertedValue($data){
    if ($data) {
        if ($data->currency_code != config('constants.salespipeline.miscellaneous.currency.default.code')) {
            if($data->turnover != 0){
                $convertedValue = Currency::convert()->from($data->currency_code)->to(config('constants.salespipeline.miscellaneous.currency.default.code'))->amount($data->turnover) ->round(2)->get();
            }else{
                $convertedValue = number_format((float)$data->turnover, 2, '.', '');
            }
            
        }else{
            $convertedValue = round($data->turnover,2);
        }            
    }
    return $convertedValue;
}

}


if (! function_exists('like_match')) {

    function like_match($pattern, $subject)
    {
        $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
        
        return (bool) preg_match("/^{$pattern}$/i", $subject);
    }
}


if (! function_exists('getKeyFromArrayAndThenValueFromAnotherArray')) {
    /**
     * Mostly used for admin report pages
     *
     * @return string
     */

    function getKeyFromArrayAndThenValueFromAnotherArray($label,$firstArr,$secondArr){            
               
            $label = formatString($label,false,true);
            foreach ($firstArr as $key => $value) {                             
                if($label === formatString($value,false,true)){
                    $newKey = $key;
                }
            }                    


        return $secondArr[$newKey];             
    }
}


if (! function_exists('checkNotIsset')) {

    function checkNotIsset($val) {
        return !isset($val) ? true : false;
    }
}


if (! function_exists('formatLaravelErrorsInHtml')) {
    /**
     * Format Laravel Validations Errors in HTML used in salespipeline module
     *
     * @return string
     */

    function formatLaravelErrorsInHtml(array $errors, $excelRow = null) {

        $html = '';
        if(isset($excelRow)){ 
            $html .= 'There are following errors in excel file at row '.$excelRow.'. ';
        }
        foreach ($errors as $key => $error) {
            if(is_array($error)) {
                foreach ($error as $key => $value) {
                    $html .= ' '.$value.'';                   
                }
            }
        }
        $html .= '';
        return $html;
    }
}

if (! function_exists('fn_print_r')) {
    /**
     * A simple die method with css
     * 
     */

    function fn_print_r($val,$string='arr',$die = false) {
                if ($val) {
                    echo '<h2>'.$string.'</h2>'; echo '<br>'; echo "<pre class='sf-dump'>"; print_r($val); echo "</pre>";
                }
                if ($die === true) {
                    die();
                }
    }
}




if (! function_exists('getNotificationContent')) {
    /**
     * Get activity and notification data depending on the notificaiton type/slug
     * Some notification and activity content will directlly be coming form their respective notifcation file
     *
     * @return string
     */

    function getNotificationContent($action,$contentType = 'activity', $model = null) {

        $activity_content = 'Unavailable';
        $notification_content = 'Unavailable';
        $actionSlug = config('constants.notifications.action_slug');
        $company_name = config('company_name') ?? config('app.name'); 
        try {
        switch ($action) {
            case $actionSlug[0]:
                $activity_content = 'A new deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b> was created';
                    break;
            case $actionSlug[1]:
                $activity_content = 'A deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b> was updated';
                    break;
            case $actionSlug[2]:
                $activity_content = 'Deals were exported';
                    break;
            case $actionSlug[3]:
                $activity_content = 'Deals were imported';
                    break;
            case $actionSlug[4]:
                $activity_content = 'A deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b> ownership was changed to '.$model->owner.'';
                    break;
            case $actionSlug[5]:
                $activity_content = $model['content'];
                    break;
            case $actionSlug[6]:
                $activity_content = 'Unavailable';
                    break;
            case $actionSlug[7]:                
                $activity_content = 'A note was created in the Deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b>';
                    break;
            case $actionSlug[8]:
                $activity_content = 'A note was updated in the Deal <b>(<a href="'.url('deal/'.$model->deal_id).'" target="_blank">'.$model->deal_name.'</a>)/<b>';
                    break;
            case $actionSlug[9]:
                $activity_content = 'A deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->title.'</a>)</b> organisation fields were updated';
                    break;
            case $actionSlug[10]:
                $activity_content = 'A deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b> person details were updated';
                    break;
            case $actionSlug[11]:
                $activity_content = 'Files were uploaded for the deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b>';
                    break;
            case $actionSlug[12]:
                $activity_content = 'A new activity was created for the deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b>';
                    break;
            case $actionSlug[13]:
                $activity_content = 'A activity was updated for the deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b>';
                    break; 
            case $actionSlug[14]:
                $activity_content = 'A activity was deleted from the deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b>';
                    break;
            case $actionSlug[15]:
                $activity_content = $model['userRequest']['middle_content']. ' from <b><a href="'.url('user/'.$model['admin']->username).'" target="_blank">'.$model['admin']->username.'</a></b>';
                $notification_content = $model['userRequest']['middle_content'].' please <a href="'.$model['userRequest']['acceptUrl'].'" target="_blank" style="color: #218721;">accept</a> or <a href="'.$model['userRequest']['denyUrl'].'" target="_blank" style="color: #FF4727;">deny</a> the request';
                    break; 
            case $actionSlug[16]:                
                $activity_content = 'A create project <b>('.$model['userRequest']->request_data['name'].')</b> request was approved';
                $notification_content = 'Your create project <b>('.$model['userRequest']->request_data['name'].')</b> request is approved. Please refresh the site if you are not able to see the project';
                    break;
            case $actionSlug[17]:
                $activity_content = 'A create project <b>('.$model['userRequest']->request_data['name'].')</b> request was denied';
                $notification_content = 'Your create project <b>('.$model['userRequest']->request_data['name'].')</b> request is denied';
                    break; 
            case $actionSlug[18]:
                $activity_content = $model['userRequest']['middle_content']. ' from <b><a href="'.url('user/'.$model['admin']->username).'" target="_blank">'.$model['admin']->username.'</a></b>';
                $notification_content = $model['userRequest']['middle_content'].' please <a href="'.$model['userRequest']['acceptUrl'].'" target="_blank" style="color: #218721;">accept</a> or <a href="'.$model['userRequest']['denyUrl'].'" target="_blank" style="color: #FF4727;">deny</a> the request';
                    break;
            case $actionSlug[19]:
                $activity_content = 'A join project <b>('.$model['project']->name.')</b> request was approved';
                $notification_content = 'Your join project <b>('.$model['project']->name.')</b> request is approved. Please refresh the site if you are not able to see yourself in the project';
                    break; 
            case $actionSlug[20]:
                $activity_content = 'A join project <b>('.$model['project']->name.')</b> request was denied';
                $notification_content = 'Your join project <b>('.$model['project']->name.')</b> request is denied';
                    break;
            case $actionSlug[21]:
                $activity_content = $model['userRequest']['middle_content']. ' from <b><a href="'.url('user/'.$model['admin']->username).'" target="_blank">'.$model['admin']->username.'</a></b>';
                $notification_content = $model['userRequest']['middle_content'].' please <a href="'.$model['userRequest']['acceptUrl'].'" target="_blank" style="color: #218721;">accept</a> or <a href="'.$model['userRequest']['denyUrl'].'" target="_blank" style="color: #FF4727;">deny</a> the request';
                    break;
            case $actionSlug[22]:
                $activity_content = 'A create project <b>('.$model['userRequest']->request_data['name'].')</b> request for deal (<a href="'.config('app.url').'/deal/'.$model['userRequest']->request_data['deal']['id'].'" target="_blank"><strong>'.$model['userRequest']->request_data['deal']['deal_name'].'</strong></a>) request was approved';
                $notification_content = 'Your create project reqeust <b>('.$model['userRequest']->request_data['name'].')</b> request is approved for deal (<a href="'.config('app.url').'/deal/'.$model['userRequest']->request_data['deal']['id'].'" target="_blank"><strong>'.$model['userRequest']->request_data['deal']['deal_name'].'</strong></a>). Please refresh the page if you are not able to see the project';
                    break;
            case $actionSlug[23]:
                $activity_content = 'A create project <b>('.$model['userRequest']->request_data['name'].')</b> request was denied for deal (<a href="'.config('app.url').'/deal/'.$model['userRequest']->request_data['deal']['id'].'" target="_blank"><strong>'.$model['userRequest']->request_data['deal']['deal_name'].'</strong></a>) was denied';
                $notification_content = 'Your create project <b>('.$model['userRequest']->request_data['name'].')</b> request is denied for deal (<a href="'.config('app.url').'/deal/'.$model['userRequest']->request_data['deal']['id'].'" target="_blank"><strong>'.$model['userRequest']->request_data['deal']['deal_name'].'</strong></a>)';
                    break;
            case $actionSlug[24]:
                $activity_content = 'A deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b> was deleted';                
                    break;
            case $actionSlug[25]:
                $activity_content = 'A deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b> note was deleted';                
                    break;
            case $actionSlug[26]:
                $activity_content = 'A deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b> status was changed to '.$model->deal_status.'';
                    break;
            case $actionSlug[27]:
                $activity_content = 'A deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b> stage was changed to '.$model->deal_current_stage.'';                
                    break;
            case $actionSlug[28]:
                $activity_content = 'A deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b> invoice was created';                
                    break;
            case $actionSlug[29]:
                $activity_content = 'A deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b> invoice was updated';
                    break;
            case $actionSlug[30]:
                $activity_content = 'A deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b> invoice default fields were updated';
                    break;
            case $actionSlug[31]:
                $activity_content = 'A deal <b>(<a href="'.url('deal/'.$model->deal_id).'" target="_blank">'.$model->deal_name.'</a>)</b> color was changed to '.$model->deal_current_color_label.'';                
                    break;
            case $actionSlug[32]:
                $activity_content = 'A deal <b>(<a href="'.url('deal/'.$model->id).'" target="_blank">'.$model->deal_name.'</a>)</b> category was changed to '.$model->deal_category_name.'';
                    break;
            case $actionSlug[33]:
                $activity_content = 'A asset with id <b>'.$model['asset']->id.'</b> was created in project <b>(<a href="'.url('project/'.$model['project']->id.'/assets').'" target="_blank">'.$model['project']->name.'</a>)</b>';
                    break;
            case $actionSlug[34]:
                $middle_content = isset($model['asset']->asset_type) ? 'type name  <b>'.$model['asset']->asset_type.'</b>' : 'id <b>'.$model['asset']->id.'</b>';
                $activity_content = 'A asset with '.$middle_content.' was updated in project <b>(<a href="'.url('project/'.$model['project']->id.'/assets').'" target="_blank">'.$model['project']->name.'</a>)</b>';
                    break;
            case $actionSlug[35]:
                $middle_content = isset($model['asset']->asset_type) ? 'type name  <b>'.$model['asset']->asset_type.'</b>' : 'id <b>'.$model['asset']->id.'</b>';
                $activity_content = 'A asset with '.$middle_content.' was deleted from project <b>(<a href="'.url('project/'.$model['project']->id.'/assets').'" target="_blank">'.$model['project']->name.'</a>)</b>';
                    break;
            case $actionSlug[36]:
                $activity_content = 'A new project <b>(<a href="'.url('project/'.$model->id.'/detail').'" target="_blank">'.$model->name.'</a>)</b> was created';
                    break;
            case $actionSlug[37]:
                $activity_content = 'A project <b>(<a href="'.url('project/'.$model->id.'/detail').'" target="_blank">'.$model->name.'</a>)</b> details were updated';
                    break;
            case $actionSlug[38]:
                $activity_content = 'A project <b>(<a href="'.url('project/'.$model->id.'/detail').'" target="_blank">'.$model->name.'</a>)</b> was deleted';
                    break;
            case $actionSlug[39]:
                $activity_content = 'A new task with id <b>'.$model['task']->id.'</b> was created in project <b>(<a href="'.url('project/'.$model['project']->id.'/tasks').'" target="_blank">'.$model['project']->name.'</a>)</b> for <b><a href="'.url('user/'.$model['task']->user->username).'" target="_blank">'.$model['task']->user->username.'</a></b>';
                    break;
            case $actionSlug[40]:
                $middle_content = isset($model['task']->name) ? 'name  <b>'.$model['task']->name.'</b>' : 'id <b>'.$model['task']->id.'</b>';
                $activity_content = 'A task with '.$middle_content.' was updated in project <b>(<a href="'.url('project/'.$model['project']->id.'/tasks').'" target="_blank">'.$model['project']->name.'</a>)</b> for <b><a href="'.url('user/'.$model['task']->user->username).'" target="_blank">'.$model['task']->user->username.'</a></b>';
                    break;
            case $actionSlug[41]:
                $middle_content = isset($model['task']->name) ? 'name  <b>'.$model['task']->name.'</b>' : 'id <b>'.$model['task']->id.'</b>';
                $activity_content = 'A task with '.$middle_content.' was deleted from project <b>(<a href="'.url('project/'.$model['project']->id.'/tasks').'" target="_blank">'.$model['project']->name.'</a>)</b> for <b><a href="'.url('user/'.$model['task']->user->username).'" target="_blank">'.$model['task']->user->username.'</a></b>';
                    break;            
            case $actionSlug[42]:
                $activity_content = isset($model) ? 'A user <b>(<a href="'.url('user/'.$model).'" target="_blank">'.$model.'</a>)</b> profile was updated' : 'User updated its profile';
                    break;
            case $actionSlug[43]:
                $activity_content = 'User <b>(<a href="'.url('user/'.$model->username).'" target="_blank">'.$model->username.'</a>)</b> account was deleted.';
                    break;
            case $actionSlug[44]:
                $activity_content = 'User updated its account details.';
                    break;
            case $actionSlug[45]:
                $activity_content = 'User updated its profile image.';
                    break;
            case $actionSlug[46]:
                $activity_content = 'A new member <b>(<a href="'.url('user/'.$model['member']->username).'" target="_blank">'.$model['member']->username.'</a>)</b> added in project <b>(<a href="'.url('project/'.$model['project']->id.'/detail').'" target="_blank">'.$model['project']->name.'</a>)</b>';
                    break;
            case $actionSlug[47]:
                $activity_content = 'A member <b>(<a href="'.url('user/'.$model['member']->username).'" target="_blank">'.$model['member']->username.'</a>)</b> was removed from project <b>(<a href="'.url('project/'.$model['project']->id.'/detail').'" target="_blank">'.$model['project']->name.'</a>)</b>';
                    break;
            case $actionSlug[48]:
                $activity_content = 'A new user <b>('.$model['user']->username.')</b> has requested to join the '.$company_name.'';
                $notification_content = 'A new user <b>('.$model['user']->username.')</b> has requested to join the '.$company_name.'. Please <a href="'.$model['user']->accept.'" target="_blank" style="color: #218721;">accept</a> or <a href="'.$model['user']->deny.'" target="_blank" style="color: #FF4727;">deny</a> the request';
                    break;
            case $actionSlug[49]:                
                $activity_content = 'A user <b>('.$model['user']->username.')</b> request to join '.$company_name.' was accepted';
                $notification_content = 'Welcome to '.$company_name.'';
                    break;
            case $actionSlug[50]:
                $activity_content = 'A user <b>('.$model->username.')</b> request to join '.$company_name.' was rejected';               
                    break;
            case $actionSlug[51]:
                $activity_content = 'A user with email <b>('.$model['to_email'].')</b> was invited to register on portal';               
                    break;
            default:
                $activity_content = 'Unavailable';
                $notification_content = 'Unavailable';
                    break;
         }
        } catch (Exception $exception) {
            \Log::error($exception);
        }
        return ($contentType == 'activity') ? $activity_content : $notification_content;
    }
}


if (! function_exists('sendDynamicNotification')) {
    /**
     * Semd notification after most of the actions, Used mostly
     */

    function sendDynamicNotification($model,$dynamicData = [],$user = null) {
        auth()->user()->notify(new DynamicNotification($model, $dynamicData, $user));
    }
}


if (! function_exists('sendDynamicNotificationNow')) {
    /**
     * Semd notification after most of the actions
     */

    function sendDynamicNotificationNow($model,$dynamicData = [],$user = null) {
        Notification::sendNow(auth()->user(), new DynamicNotification($model, $dynamicData, $user));        
    }
}

}




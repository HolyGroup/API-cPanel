<?php
/**
 * @author MartinDev <martin@martindev.fr>
 * @version 2.0.1
 * A Simple and fast cPanel API with PHP and CURL (JSON-API)
 */
class cPanel{

    private $endpoint;
    private $username;
    private $password;


    public function __construct(string $endpoint, string $username, string $password)
    {
        $this->endpoint = $endpoint;
        $this->username = $username;
        $this->password = $password;
    }
    /**
     * Permet de faire des Requête
     * @param string $query - Requête a faire au serveur Web
     * @param int $port - Port sur lequel on doit faire la Requête
     */
    public function Query($query, $port = 2087){

        $query = 'https://'.$this->endpoint.':'. $port.$query;
        $curl = curl_init();
		$header[0] = "Authorization: Basic " . base64_encode($this->username. ":" . $this->password) . "\n\r";
        curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HEADER         => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_URL            => $query
    
        ]);
		$result = curl_exec($curl);
            if($result === false){ return false; /*throw new Exception("Erreur CURL Pour la Requête : ". $query . '\n'. curl_error($curl));*/ }
        
		curl_close($curl);
		$result = json_encode($result,1);
		return $result;
    }

    /**
     * Permet de créer un compte Cpanel
     * @param string $plan - Plan WHM 
     * @return array - Tableau contenant les informations du compte créé
     */
    public function create($Plan){

        $User = 'web'.rand(3000, 4000);
        $Domain = $User. ".votredomaine.fr";
        $Password = "PASSWORD-".rand(1, 99999);
        $Email = $User. "@votredomaine.fr";
        $qry = '/json-api/createacct?username='.$User.'
        &plan='.$Plan.'
        &ip=n&cpmod=x3&password='.$Password.'
        &contactemail='.$Email.'
        &domain='.$Domain.'
        &useregns=0&reseller=0';
        $result = $this->Query($qry);
        if($result){
            $parse = json_decode($result, JSON_UNESCAPED_SLASHES);
            return [
                'User'      => $User,
                'Domain'    => $Domain,
                'Password'  => $Password,
                'Email'     => $Email,
                'Plan'      => $Plan,
                'Result'    => $parse
            ];
        }else{
            return false;
        }
    }

    /**
     * Permet de suspendre un compte Cpanel
     * @param string $User - Nom de l'utilisateur a suspendre (web1234)
     * @param string $Reason - Raison de la suspension
     * @return array - Tableau contenant les informations du compte suspendus
     */
    public function suspend($User, $Reason = "Expiration"){
        $qry = '/json-api/suspendacct?user='.$User.'&reason='. $Reason;
        $result = $this->Query($qry);
        if($result){
            $parse = json_decode($result, JSON_UNESCAPED_SLASHES);
            return [
                'User'      => $User,
                'Reason'    => $Reason,
                'Result'    => $parse
            ];
        }else{
            return false;
        }
    }

    /**
     * Permet de unsuspendre un compte Cpanel
     * @param string $User - Nom de l'utilisateur a unsuspendre (web1234)
     * @return array - Tableau contenant les informations du compte unsuspendus
     */
    public function unsuspend($User){
        $qry = '/json-api/unsuspendacct?user='.$User;
        $result = $this->Query($qry);
        if($result){
            $parse = json_decode($result, JSON_UNESCAPED_SLASHES);
            return [
                'User'      => $User,
                'Result'    => $parse
            ];
        }else{
            return false;
        }
    }

    /**
     * Permet de backup un compte Cpanel
     * @param string $User - Nom de l'utilisateur a sauvegarder (web1234)
     * @param string $Password - Mot de passe de l'utilisateur a sauvegarder (P@SS-OH-1234)
     * @param string $Host - Hôte a sauvegarder (web1234.votredomaine.fr)
     * @return array - Tableau contenant les informations du compte de backup
     */
    public function backup($User, $Password, $Host){
        $qry = '/execute/Backup/fullbackup_to_ftp?variant=active&username='.$User.
        '&password='.$Password.
        '&host='.$Host.
        '&port=21&directory=%2Fpublic_ftp&email=username%40example.com';
        $result = $this->Query($qry, 2083);
        if($result){
            $parse = json_decode($result, JSON_UNESCAPED_SLASHES);
            return [
                'User'      => $User,
                'Password'  => $Password,
                'Host'      => $Host,
                'Result'    => $parse
            ];
        }else{
            return false;
        }
    }

    /**
     * @param string $User - Nom de l'utilisateur a réinitialiser mot de passe 
     * @param string $Password - Nouveau mot de passe du compte 
     * @return array - Tableau contenant les informations du compte 
     */
    public function resetPassword($User, $Password, $enabledigest = 0){
        $qry = '/json-api/passwd?api.version=1&user='.$User.'&password='.$Password.'&enabledigest='. $enabledigest;
        $result = $this->Query($qry);

        if($result){
            $parse = json_decode($result, JSON_UNESCAPED_SLASHES);
            return [
                'User'      => $User,
                'Password'  => $Password, 
                'Result'    => $parse
            ];
        }else{
            return false;
        }
    }
    
    /**
     * Permet de supprimer un compte Cpanel
     * @param string $User - Nom de l'utilisateur a supprimer 
     * @param string $Reason - Raison de la suppression
     * @return array - Tableau contenant les informations du compte suspendus
     */
    public function delete($User, $Reason = "Expiration"){
        $qry = '/json-api/removeacct?user='.$User.'&reason='. $Reason;
        $result = $this->Query($qry);
        if($result){
            $parse = json_decode($result, JSON_UNESCAPED_SLASHES);
            return [
                'User'      => $User,
                'Reason'    => $Reason,
                'Result'    => $parse
            ];
        }else{
            return false;
        }
    }

}

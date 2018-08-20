# Za delovanje preusmeritev na nivoju strežnika Apache moramo
# vklopiti modul rewrite. To naredimo z naslednjim ukazom

sudo a2enmod rewrite

# V datoteko /etc/apache2/sites-available/000-default.conf
# pod vrstico Alias /netbeans... dodamo spodnjo vsebino

Alias /netbeans/ "/home/ep/NetBeansProjects/"
<Directory /home/ep/NetBeansProjects/>
        Require all granted
        AllowOverride All
</Directory>

# Enako vsebino dodamo v konfiguracijo datoteko, ki v Apacheju določa strežbo 
# datoteko po protokolu SSL (default-ssl.conf)
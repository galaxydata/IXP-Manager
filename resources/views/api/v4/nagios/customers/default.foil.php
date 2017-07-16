#
# This file contains Nagios configuration for customers' VLAN interfaces
#
# WARNING: this file is automatically generated using the
#   api/v4/nagios/customers/{vlanid}/{protocol} API call to IXP Manager.
# Any local changes made to this script will be lost.
#
# VLAN id: <?= $t->vlan->getId() ?>; protocol: ipv<?= $t->protocol ?>; tag: <?= $t->vlan->getNumber() ?>; name: <?= $t->vlan->getName() ?>.
#
# Generated: <?= date( 'Y-m-d H:i:s' ) . "\n" ?>
#

<?php
    // some arrays for later:
    $all       = [];
    $switches  = [];
    $cabinets  = [];
    $locations = [];
?>

<?php foreach( $t->vlis as $vli ): ?>

###############################################################################################
###
### <?= $vli['cname'] . "\n" ?>
###
### <?= $vli['location_name'] ?> / <?= $vli['abrevcname'] ?> / <?=  $vli['sname'] ?>.
###

<?php
    if( !$vli['enabled'] || !$vli['address'] ) {
        echo "\n\n ## ipv{$t->protocol} not enabled / no address configured, skipping\n\n";
        continue;
    }

    $hostname = preg_replace( '/[^a-zA-Z0-9]/', '-', strtolower( $vli['abrevcname'] ) ) . '-as' . $vli['autsys'] . '-ipv' . $t->protocol . '-vlantag' . $vli['vtag'] . '-vliid' . $vli['vliid'];

    $all[]                                = $hostname;
    $switches[ $vli['sname'] ][]          = $hostname;
    $cabinets[ $vli['cabname'] ][]        = $hostname;
    $locations[ $vli['location_name'] ][] = $hostname;
?>

### Host: <?= $vli['address'] ?> / <?= $vli['hostname'] ?> / <?= $vli['vname'] ?>.

define host {
    use                     <?= $t->host_definition ?>

    host_name               <?= $hostname ?>

    alias                   <?= $vli['cname'] ?> / <?= $vli['sname'] ?> / <?= $vli['vname'] ?>.
    address                 <?= $vli['address'] ?>

    check_command           <?= $t->host_check_command ?>

    check_period            <?= $t->check_period ?>

    max_check_attempts      <?= $t->max_check_attempts ?>

    notification_interval   <?= $t->notification_interval ?>

    notification_period     <?= $t->notification_period ?>

    notification_options    <?= $t->host_notification_options ?>

    contact_groups          <?= $t->contact_groups ?>

}



### Service: <?= $vli['address']  ?> / <?= $vli['hostname'] ?> / <?= $vli['vname'] ?>.

define service {
    use                     <?= $t->service_definition ?>

    host_name               <?= $hostname ?>

    service_description     PING<?= $vli['busyhost'] ? '-busy' : '' ?>

    check_command           <?= $vli['busyhost'] ? $t->pingbusy_check_command : $t->ping_check_command ?>

    check_period            <?= $t->check_period ?>

    max_check_attempts      <?= $t->max_check_attempts ?>

    check_interval          <?= $t->check_interval ?>

    retry_check_interval    <?= $t->retry_check_interval ?>

    contact_groups          <?= $t->contact_groups ?>

    notification_interval   <?= $t->notification_interval ?>

    notification_period     <?= $t->notification_period ?>

    notification_options    <?= $t->service_notification_options ?>

}

<?php endforeach; ?>




###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################


###############################################################################################
###
### Group: by switch
###
###
###

<?php foreach( $switches as $k => $c ): ?>

    define hostgroup {
    hostgroup_name  switch-ipv<?= $t->protocol ?>-vlanid-<?= $t->vlan->getId() ?>-<?= preg_replace( '/[^a-zA-Z0-9]/', '-', strtolower( $k ) ) ?>

    alias           All IPv<?= $t->protocol ?> Members Connected to Switch <?= $k ?> for VLAN <?= $t->vlan->getName() ?>

    members         <?= $t->softwrap( $c, 1, ', ', ', \\', 20 ) ?>

}

<?php endforeach; ?>


###############################################################################################
###
### Group: by cabinet
###
###
###

<?php foreach( $cabinets as $k => $c ): ?>

define hostgroup {
    hostgroup_name  cabinet-ipv<?= $t->protocol ?>-vlanid-<?= $t->vlan->getId() ?>-<?= preg_replace( '/[^a-zA-Z0-9]/', '-', strtolower( $k ) ) ?>

    alias           All IPv<?= $t->protocol ?> Members in Cabinet <?= $k ?> for VLAN <?= $t->vlan->getName() ?>

    members         <?= $t->softwrap( $c, 1, ', ', ', \\', 20 ) ?>

}

<?php endforeach; ?>


###############################################################################################
###
### Group: by location
###
###
###

<?php foreach( $locations as $k => $l ): ?>

define hostgroup {
    hostgroup_name  location-ipv<?= $t->protocol ?>-vlanid-<?= $t->vlan->getId() ?>-<?= preg_replace( '/[^a-zA-Z0-9]/', '-', strtolower( $k ) ) ?>

    alias           All IPv<?= $t->protocol ?> Members at Location <?= $k ?> for VLAN <?= $t->vlan->getName() ?>

    members         <?= $t->softwrap( $l, 1, ', ', ', \\', 20 ) ?>

}

<?php endforeach; ?>


###############################################################################################
###
### Group: all
###
###
###

define hostgroup {
    hostgroup_name  all-ipv<?= $t->protocol ?>-vlanid-<?= $t->vlan->getId() ?>

    alias           All IPv<?= $t->protocol ?> Members for VLAN <?= $t->vlan->getName() ?>

    members         <?= $t->softwrap( $all, 1, ', ', ', \\', 20 ) ?>

}


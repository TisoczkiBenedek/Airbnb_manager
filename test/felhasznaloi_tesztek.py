from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
import random
import time


web = webdriver.Chrome()
url = 'http://localhost/13c-tisoczki/Airbnb_manager/alapoldal.html'
web.get(url)
time.sleep(2)
def regisztracioTeszt():
    
    elem = web.find_elements(By.CLASS_NAME, "btn")
    regisztracio = elem[1]
    regisztracio.send_keys(Keys.ENTER)
    title = web.title
    print("Az oldal címe:", title)
    email = web.find_element(By.ID, "email")
    time.sleep(2)
    email.send_keys("tesztemail@gmail.com")
    jelszo = web.find_element(By.ID, "jelszo")
    jelszo.send_keys("Jelszoteszt2")
    megj = web.find_element(By.ID, "szem")
    time.sleep(2)
    megj.click()
    if(jelszo.get_property("type")== "text"):
        print("Jelszó megjelenítése működik!")
    else:
        print("Jelszó megjelenítése hibás!")
    vnev = web.find_element(By.ID, "vnev")
    vnev.send_keys("Gipsz")
    time.sleep(1)
    knev = web.find_element(By.ID, "knev")
    knev.send_keys("János")
    tel = web.find_element(By.ID, "telefonszam")
    tel.send_keys("06301234567")
    megye = web.find_element(By.ID, "megye")
    megye.click()
    opt = web.find_elements(By.TAG_NAME, "option")
    index = random.randint(0, len(opt)-1)
    opt[index].click()
    time.sleep(2)
    megye.click()
    radiok = web.find_elements(By.NAME, "valasztas")
    index2 = random.randint(0, 1)
    radiok[index2].click()
    time.sleep(1)
    gomb = web.find_element(By.ID, "gomb")
    gomb.send_keys(Keys.ENTER)
    web.switch_to.alert.accept()
    #alert = web.find_element(By.LINK_TEXT, "Már van ezzel az e-mail címmel regisztrált felhasználó! Kérem próbáljon meg bejelentkezni!")
    #alert.click()
    time.sleep(2)

regisztracioTeszt()
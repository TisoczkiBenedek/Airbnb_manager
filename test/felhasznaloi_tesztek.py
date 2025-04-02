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
    time.sleep(2)
    alert = web.switch_to.alert
    alert.accept()
    #alert = web.find_element(By.LINK_TEXT, "Már van ezzel az e-mail címmel regisztrált felhasználó! Kérem próbáljon meg bejelentkezni!")
    #alert.click()
    time.sleep(2)
def bejelentkezesTeszt():
    web.get(url)
    elem = web.find_elements(By.CLASS_NAME, "btn")
    regisztracio = elem[0]
    regisztracio.send_keys(Keys.ENTER)
    title = web.title
    print("Az oldal címe:", title)
    time.sleep(2)
    email = web.find_element(By.ID, "email")
    email.send_keys("tesztemail@gmail.com")
    jelszo = web.find_element(By.ID, "jelszo")
    jelszo.send_keys("Jelszoteszt2")
    time.sleep(2)
    megj = web.find_element(By.ID, "szem")
    time.sleep(2)
    megj.click()
    if(jelszo.get_property("type")== "text"):
        print("Jelszó megjelenítése működik!")
    else:
        print("Jelszó megjelenítése hibás!")
    time.sleep(2)
    gomb = web.find_element(By.ID, "gomb")
    gomb.send_keys(Keys.ENTER)
    
    
    time.sleep(2)
def lakasFeltoltes():
    gombok = web.find_elements(By.CLASS_NAME, "btn")
    gombok[1].click()
    time.sleep(2)
    lakasNev = web.find_element(By.ID, "lakasNev")
    lakasNev.send_keys("Teszt1")
    lakCim = web.find_element(By.ID, "lakcim")
    lakCim.send_keys("8100 Város, Teszt utca 1/L")
    terulet = web.find_element(By.ID, "terulet")
    terulet.send_keys("25")
    medence = web.find_element(By.ID, "medence")
    medence.click()
    select = web.find_element(By.ID, "megye")
    opt = select.find_elements(By.TAG_NAME, "option")
    index = random.randint(1, len(opt)-1)
    opt[index].click()
    time.sleep(2)
    lakasAdat = web.find_element(By.ID, "lakasAdatok")
    lakasAdat.send_keys("Szöveg szöveg szöveg123")
    kepfeltoltes = web.find_element(By.NAME, "kepFeltoltes")
    kepfeltoltes.send_keys("C:\\xampp\\htdocs\\13c-tisoczki\\Airbnb_manager\\test\\tesztkep.png")
    time.sleep(2)
    esemenyfelt = web.find_element(By.NAME, "naptarFeltoltes")
    esemenyfelt.send_keys("C:\\xampp\\htdocs\\13c-tisoczki\\Airbnb_manager\\test\\esemenyek.ics")
    time.sleep(2)
    felt = ""
    for elem in gombok:
        if(elem.text == "Feltöltés"):
            felt = elem
    felt.click()
    time.sleep(2)
def lakasEllenorzes(nev):
    kartyacim = web.find_elements(By.TAG_NAME, "h5")
    van = False
    for elem in kartyacim:
        if(elem.text == nev):
            van = True
            break
    if(van):
        print("Lakás feltöltése/módosítása/törlése sikeres")
    else:
        print("Lakás feltöltése/módosítása/törlése hibás")
def lakasModositas():
    cardbody = web.find_elements(By.CLASS_NAME, "card-body")
    gombok = cardbody[0].find_elements(By.CLASS_NAME, "btn")
    modosit = ""
    for elem in gombok:
        if(elem.get_attribute("value")== "Módosítás"):
            modosit = elem
            break
    modosit.click()
    time.sleep(2)
    lakasnev = web.find_element(By.NAME, "nev")
    lakasnev.clear()
    lakasnev.send_keys("Teszt2")
    time.sleep(2)
    form = web.find_element(By.ID, "modositForm")
    mentes = form.find_element(By.TAG_NAME, "button")
    mentes.click()
    time.sleep(2)
    lakasEllenorzes("Teszt2")
def lakasTorles():
    cardbody = web.find_elements(By.CLASS_NAME, "card-body")
    gombok = cardbody[0].find_elements(By.CLASS_NAME, "btn")
    torol = ""
    for elem in gombok:
        if(elem.get_attribute("value")== "Törlés"):
            torol = elem
            break
    torol.click()
    time.sleep(2)
    torlesiegn = web.find_element(By.ID, "torlesIgen")
    torlesiegn.click()
    time.sleep(2)
    lakasEllenorzes("")
def naptarEllenorzes():
    cardbody = web.find_elements(By.CLASS_NAME, "card-body")
    gombok = cardbody[0].find_elements(By.CLASS_NAME, "btn")
    naptar = ""
    for elem in gombok:
        if(elem.get_attribute("value")== "Naptár"):
            naptar = elem
            break
    naptar.click()
    print("Az oldal címe: ", web.title)

#regisztracioTeszt()
bejelentkezesTeszt()
lakasFeltoltes()
#lakasEllenorzes("Teszt1")
#lakasModositas()
naptarEllenorzes()
#lakasTorles()
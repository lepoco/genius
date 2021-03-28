// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System;
using System.Collections.Generic;

namespace Genius.Code.UI
{
    public static class Samples
    {
        public static Engine.System SampleExpert
        {
            get => new Engine.System
            {
                Name = "Expert system supporting the diagnosis of diseases",
                Description = "A computer program designed together with the medical university to aid in the diagnosis of diseases.",

                KnowledgeBase = new Engine.KnowledgeBase
                {
                    Products = new List<Engine.Product>
                    {
                        new Engine.Product { ID = 0, Name = "Przeziębienie" },
                        new Engine.Product { ID = 1, Name = "Grypa" },
                        new Engine.Product { ID = 2, Name = "Cukrzyca" },
                        new Engine.Product { ID = 3, Name = "Zawał serca" },
                        new Engine.Product { ID = 4, Name = "Choroba niedokrwienna serca" },
                        new Engine.Product { ID = 5, Name = "Otyłość" },
                        new Engine.Product { ID = 6, Name = "Astma" },
                        new Engine.Product { ID = 7, Name = "Nowotwór żołądka" },
                        new Engine.Product { ID = 8, Name = "Zapalenie wyrostka robaczkowego" },
                        new Engine.Product { ID = 9, Name = "Nowotwór kolczysto-komórkowy skóry" },
                        new Engine.Product { ID = 10, Name = "Depresja" }
                    },
                    Conditions = new List<Engine.Condition>
                    {
                        new Engine.Condition { ID = 0, Name = "Ból skroni" },
                        new Engine.Condition { ID = 1, Name = "Ból głowy" },
                        new Engine.Condition { ID = 2, Name = "Ból pleców" },
                        new Engine.Condition { ID = 3, Name = "Bóle stawów" },
                        new Engine.Condition { ID = 4, Name = "Bóle mięśni" },
                        new Engine.Condition { ID = 5, Name = "Ból gardła" },
                        new Engine.Condition { ID = 6, Name = "Zaburzenia widzenia" },
                        new Engine.Condition { ID = 7, Name = "Dreszcze" },
                        new Engine.Condition { ID = 8, Name = "Gorączka" },
                        new Engine.Condition { ID = 9, Name = "Światłowstręt" },
                        new Engine.Condition { ID = 10, Name = "Wymioty" },
                        new Engine.Condition { ID = 11, Name = "Zatkany nos" },
                        new Engine.Condition { ID = 12, Name = "Czkawka" },
                        new Engine.Condition { ID = 13, Name = "Drgawki" },
                        new Engine.Condition { ID = 14, Name = "Kichanie" },
                        new Engine.Condition { ID = 15, Name = "Katar" },
                        new Engine.Condition { ID = 16, Name = "Kaszel" },
                        new Engine.Condition { ID = 17, Name = "Obrzęk płuc" },
                        new Engine.Condition { ID = 18, Name = "Złe samopoczucie" },
                        new Engine.Condition { ID = 19, Name = "Myśli samobójcze" },
                        new Engine.Condition { ID = 20, Name = "Ogólne rozdrażnienie" }
                    },
                    References = new List<Engine.Reference>
                    {
                        new Engine.Reference { ProductId = 0, ConditionId = 18 }
                    }
                }
            };
        }
    }
}

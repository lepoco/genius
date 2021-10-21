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
        public static Engine.ExpertSystem SampleExpert
        {
            get => new Engine.ExpertSystem
            {
                Name = "Expert system supporting the diagnosis of diseases",
                Description = "A computer program designed together with the medical university to aid in the diagnosis of diseases.",
                Question = "Does the disease you are looking for have a symptom:",

                KnowledgeBase = new Engine.KnowledgeBase
                {
                    Products = new List<Engine.Product>
                    {
                        new Engine.Product { ID = 0, Name = "Przeziębienie" },
                        new Engine.Product { ID = 1, Name = "Grypa" },
                        new Engine.Product { ID = 2, Name = "Cukrzyca" },
                        new Engine.Product { ID = 3, Name = "Atak serca" },
                        new Engine.Product { ID = 4, Name = "Udar niedokrwienny mózgu" },
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
                        
                        new Engine.Condition { ID = 20, Name = "Ogólne rozdrażnienie" },
                        new Engine.Condition { ID = 21, Name = "Ogólne osłabienie" },
                        new Engine.Condition { ID = 22, Name = "Wzmorzone pragnienie" },
                        new Engine.Condition { ID = 23, Name = "Napady głodu" },
                        new Engine.Condition { ID = 24, Name = "Częstomocz" },
                        new Engine.Condition { ID = 25, Name = "Poluria" },
                        new Engine.Condition { ID = 26, Name = "Apatia" },
                        new Engine.Condition { ID = 27, Name = "Senność" },
                        new Engine.Condition { ID = 28, Name = "Grzybica skóry" },
                        new Engine.Condition { ID = 29, Name = "Infekcje intymne" },
                        
                        new Engine.Condition { ID = 30, Name = "Nudności" },
                        new Engine.Condition { ID = 31, Name = "Zimne poty" },
                        new Engine.Condition { ID = 32, Name = "Drętwienie kończyn" },
                        new Engine.Condition { ID = 33, Name = "Częściowe porażenie" },
                        new Engine.Condition { ID = 34, Name = "Opadanie kącika ust" },
                        new Engine.Condition { ID = 35, Name = "Drętwienie karku" }
                    },
                    References = new List<Engine.Reference>
                    {
                        new Engine.Reference { ProductId = 0, ConditionId = 1 },
                        new Engine.Reference { ProductId = 0, ConditionId = 5 },
                        new Engine.Reference { ProductId = 0, ConditionId = 8 },
                        new Engine.Reference { ProductId = 0, ConditionId = 11 },
                        new Engine.Reference { ProductId = 0, ConditionId = 15 },
                        new Engine.Reference { ProductId = 0, ConditionId = 16 },
                        new Engine.Reference { ProductId = 0, ConditionId = 18 },

                        new Engine.Reference { ProductId = 1, ConditionId = 0 },
                        new Engine.Reference { ProductId = 1, ConditionId = 1 },
                        new Engine.Reference { ProductId = 1, ConditionId = 3 },
                        new Engine.Reference { ProductId = 1, ConditionId = 4 },
                        new Engine.Reference { ProductId = 1, ConditionId = 7 },
                        new Engine.Reference { ProductId = 1, ConditionId = 8 },
                        new Engine.Reference { ProductId = 1, ConditionId = 9 },
                        new Engine.Reference { ProductId = 1, ConditionId = 20 },
                        new Engine.Reference { ProductId = 1, ConditionId = 21 },

                        new Engine.Reference { ProductId = 2, ConditionId = 21 },
                        new Engine.Reference { ProductId = 2, ConditionId = 22 },
                        new Engine.Reference { ProductId = 2, ConditionId = 23 },
                        new Engine.Reference { ProductId = 2, ConditionId = 24 },
                        new Engine.Reference { ProductId = 2, ConditionId = 25 },
                        new Engine.Reference { ProductId = 2, ConditionId = 26 },
                        new Engine.Reference { ProductId = 2, ConditionId = 27 },
                        new Engine.Reference { ProductId = 2, ConditionId = 28 },

                        new Engine.Reference { ProductId = 3, ConditionId = 1 },
                        new Engine.Reference { ProductId = 3, ConditionId = 4 },
                        new Engine.Reference { ProductId = 3, ConditionId = 7 },
                        new Engine.Reference { ProductId = 3, ConditionId = 16 },
                        new Engine.Reference { ProductId = 3, ConditionId = 21 },
                        new Engine.Reference { ProductId = 3, ConditionId = 30 },
                        new Engine.Reference { ProductId = 3, ConditionId = 31 },
                        new Engine.Reference { ProductId = 3, ConditionId = 32 },

                        new Engine.Reference { ProductId = 4, ConditionId = 6 },
                        new Engine.Reference { ProductId = 4, ConditionId = 7 },
                        new Engine.Reference { ProductId = 4, ConditionId = 9 },
                        new Engine.Reference { ProductId = 4, ConditionId = 20 },
                        new Engine.Reference { ProductId = 4, ConditionId = 21 },
                        new Engine.Reference { ProductId = 4, ConditionId = 32 },
                        new Engine.Reference { ProductId = 4, ConditionId = 33 },
                        new Engine.Reference { ProductId = 4, ConditionId = 34 },
                        new Engine.Reference { ProductId = 4, ConditionId = 35 },
                    }
                }
            };
        }
    }
}
